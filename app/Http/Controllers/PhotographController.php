<?php

namespace App\Http\Controllers;

use App\Classes\ExifData;
use App\Classes\GoogleDrive;
use App\Models\Photograph;
use App\Models\PhotographChecklist;
use App\Models\PhotographCollection;
use App\Models\PhotographEdit;
use App\Models\PhotographOtherFile;
use Auth;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Image;
use Intervention\Image\Constraint;
use Str;
use Throwable;
use Validator;

class PhotographController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('uploadEdit', 'showPhotographList');
    }

    public function showPhotographList()
    {
        return view('page.manage-photo-list');
    }

    /**
     * Show the new photo form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showNewPhotographForm()
    {
        return view('page.new-photo');
    }

    /**
     * Show the edit photo form.
     *
     * @param Photograph $photo
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showManagePhotographForm(Photograph $photo)
    {
        return view('page.manage-photo', compact('photo'));
    }

    /**
     * Creates a new photograph record.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function create(Request $request)
    {
        // Validation
        $data = $this->validate($request, [
            'guid' => 'required|unique:photographs',
            'status' => 'required|in:active,inactive',
            'name' => 'required|string|between:1,255',
            'location' => 'required|string|between:1,255',
            'description' => 'required|string|between:1,2000',
            'tags' => 'required|array|between:1,30',
            'tags.*' => 'required|string|between:1,65',
        ]);

        // Wrap everything in a transaction
        DB::beginTransaction();

        try {
            // Create the photo
            $photo = new Photograph($data);
            $photo->tags = json_encode($photo->tags);
            $photo->user()->associate(Auth::user());
            $photo->saveOrFail();

            // Create the checklist
            $photographChecklist = Auth::user()->photograph_checklist;
            if (strlen($photographChecklist) > 0) {
                $checklistRows = explode(PHP_EOL, $photographChecklist);
                foreach ($checklistRows as $index => $checklistRow) {
                    $checklist = new PhotographChecklist;
                    $checklist->sequence_number = ($index + 1);
                    $checklist->completed = false;
                    $checklist->instruction = trim($checklistRow);
                    $checklist->user()->associate(Auth::user());
                    $checklist->photograph()->associate($photo);
                    $checklist->saveOrFail();
                }
            }

            // Commit the transaction
            DB::commit();

            // Return the response
            return redirect()->intended(route('photograph.manage', ['photo' => $photo->id]));

        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Updates an existing photograph record.
     *
     * @param Request $request
     * @param Photograph $photo
     * @return \Illuminate\Http\RedirectResponse
     * @throws Throwable
     * @throws ValidationException
     */
    public function update(Request $request, Photograph $photo)
    {
        $data = $this->validate($request, [
            'status' => 'required|in:active,inactive',
            'name' => 'required|string|between:1,255',
            'location' => 'required|string|between:1,255',
            'description' => 'required|string|between:1,2000',
            'tags' => 'required|array|between:1,30',
            'tags.*' => 'required|string|between:1,65',
        ]);

        $photo->fill($data);
        $photo->saveOrFail();

        return redirect()->back()->with('status', 'Photograph successfully saved.');
    }

    /**
     * Updates a checklist item for the photograph.
     *
     * @param Request $request
     * @param PhotographChecklist $checklist
     * @return \Illuminate\Http\JsonResponse
     * @throws Throwable
     * @throws ValidationException
     */
    public function updateChecklistItem(Request $request, PhotographChecklist $checklist)
    {
        // Validation
        $data = $this->validate($request, [
            'completed' => 'required|boolean',
        ]);

        // Update the checklist
        $checklist->fill($data);
        $checklist->saveOrFail();

        // Return the response
        return response()->json($checklist, 200);
    }

    /**
     * Adds the specified photograph to a collection.
     *
     * @param Request $request
     * @param Photograph $photo
     * @return \Illuminate\Http\JsonResponse
     * @throws Throwable
     * @throws ValidationException
     */
    public function addToCollection(Request $request, Photograph $photo)
    {
        // Validation
        $data = $this->validate($request, [
            'title' => 'required|string|between:2,255',
        ]);

        // Check if this photo already belongs to the specified collection
        if ($photo->photographCollections()->where('title', '=', $data['title'])->count() > 0) {
            throw ValidationException::withMessages([
                'title' => "This photograph is already associated with the '{$data['title']}' collection",
            ]);
        }

        // Create the collection
        $collection = new PhotographCollection($data);
        $collection->user()->associate(Auth::user());
        $collection->photograph()->associate($photo);
        $collection->saveOrFail();

        // Return the response
        return response()->json($collection, 200);
    }

    /**
     * Deletes the specified photograph collection.
     *
     * @param PhotographCollection $collection
     * @return \Illuminate\Http\JsonResponse
     * @throws Throwable
     * @throws ValidationException
     */
    public function deleteCollection(PhotographCollection $collection)
    {
        $collection->delete();
        return response()->json();
    }

    /**
     * Uploads a photograph edit (the actual edited photo).
     *
     * @param Request $request
     * @param Photograph $photo
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
    public function uploadEdit(Request $request, Photograph $photo)
    {
        DB::beginTransaction();

        try {
            $data = $this->validate($request, [
                'image' => 'required|image|mimes:jpeg|max:131072',
            ]);
            if (strlen(Auth::user()->google_drive_dir_edits) <= 0) {
                throw ValidationException::withMessages([
                    'google_drive' => 'You must set the "Edited Photos Directory" on the "Profile" page before uploading edits.',
                ]);
            }

            // Upload image to Google Drive
            $google = new GoogleDrive;
            if (!$google->isAuthed()) {
                throw ValidationException::withMessages([
                    'google_drive' => 'Invalid Google Drive Session',
                ]);
            }
            $googleDir = $google->mkdirs(Auth::user()->google_drive_dir_edits);
            $photo->google_drive_file_id = $google->upload(
                $googleDir,
                "{$photo->guid}.jpg",
                'image/jpeg',
                $data['image']->getPathname()
            );
            $photo->saveOrFail();

            // Release memory
            $google = null;
            gc_collect_cycles();

            // Create the large scaled image
            $largeImage = Image::make($data['image']);
            $originalWidth = $largeImage->width();
            $originalHeight = $largeImage->height();
            $largeImage->resize(1536, 1536, function (Constraint $constraint) {
                $constraint->aspectRatio();
            });
            $largeImage->insert(resource_path('img/watermarks/large.png'), 'center');

            // Save large image to storage
            $large = new PhotographEdit;
            $large->scaled_size = 'large';
            $large->disk = 'public';
            $large->directory = 'edits-large';
            $large->filename = $photo->guid . '.jpg';
            $large->filetype = 'jpg';
            $large->original_width = $originalWidth;
            $large->original_height = $originalHeight;
            $large->scaled_width = $largeImage->width();
            $large->scaled_height = $largeImage->height();
            $large->user()->associate(Auth::user());
            $large->photograph()->associate($photo);
            $large->storeImage($largeImage);
            $large->saveOrFail();
            $large->url = $large->imageURL();

            // Release memory
            $largeImage = null;
            gc_collect_cycles();

            // Create the medium scaled image
            $mediumImage = Image::make($data['image']);
            $mediumImage->resize(1024, 1024, function (Constraint $constraint) {
                $constraint->aspectRatio();
            });
            $mediumImage->insert(resource_path('img/watermarks/medium.png'), 'bottom-right', 40, 40);

            // Save medium image to storage
            $medium = new PhotographEdit;
            $medium->scaled_size = 'medium';
            $medium->disk = 'public';
            $medium->directory = 'edits-medium';
            $medium->filename = $photo->guid . '.jpg';
            $medium->filetype = 'jpg';
            $medium->original_width = $originalWidth;
            $medium->original_height = $originalHeight;
            $medium->scaled_width = $mediumImage->width();
            $medium->scaled_height = $mediumImage->height();
            $medium->user()->associate(Auth::user());
            $medium->photograph()->associate($photo);
            $medium->storeImage($mediumImage);
            $medium->saveOrFail();
            $medium->url = $medium->imageURL();

            // Release memory
            $mediumImage = null;
            gc_collect_cycles();

            // Create the thumbnail image
            $thumbImage = Image::make($data['image']);
            $thumbImage->resize(512, 512, function (Constraint $constraint) {
                $constraint->aspectRatio();
            });

            // Save small image to storage
            $thumb = new PhotographEdit;
            $thumb->scaled_size = 'thumb';
            $thumb->disk = 'public';
            $thumb->directory = 'edits-thumb';
            $thumb->filename = $photo->guid . '.jpg';
            $thumb->filetype = 'jpg';
            $thumb->original_width = $originalWidth;
            $thumb->original_height = $originalHeight;
            $thumb->scaled_width = $thumbImage->width();
            $thumb->scaled_height = $thumbImage->height();
            $thumb->user()->associate(Auth::user());
            $thumb->photograph()->associate($photo);
            $thumb->storeImage($thumbImage);
            $thumb->saveOrFail();
            $thumb->url = $thumb->imageURL();

            // Release memory
            $thumbImage = null;
            gc_collect_cycles();

            // Commit the changes
            DB::commit();

            // Return the response
            return response()->make(json_encode(compact('large', 'medium', 'thumb')), 201);

        } catch (ValidationException $e) {
            DB::rollBack();
            $message = 'Form Errors:';
            foreach ($e->validator->getMessageBag()->getMessages() as $field => $error) {
                $errorMessage = implode(', ', $error);
                $message .= " {$field} - {$errorMessage}";
            }
            return response()->make($message, 422);

        } catch (Throwable $e) {
            DB::rollBack();
            return response()->make("Error: {$e->getMessage()}", 419);
        }
    }

    /**
     * Uploads a photograph file attachment (RAW, XMP, etc).
     *
     * @param Request $request
     * @param Photograph $photo
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     * @throws ValidationException
     * @throws Exception
     */
    public function uploadOther(Request $request, Photograph $photo)
    {
        try {
            // Validation
            $data = $this->validate($request, [
                'other_type' => 'required|in:raw,meta',
                'file' => 'required|file|max:262144',
            ]);

            // Specific file type validation
            switch ($data['other_type']) {
                case 'raw':
                    $googleDriveDir = Auth::user()->google_drive_dir_raws;
                    $googleDriveDirDesc = 'Raw Files Directory';
                    $allowedMimeTypes = '3fr,ari,arw,bay,bmp,braw,crw,cr2,cr3,cap,data,dcs,dcr,dng,drf,eip,erf,fff,gpr,iiq,jpeg,k25,kdc,mdc,mef,mos,mrw,nef,nrw,obm,orf,pef,png,ptx,pxn,r3d,raf,raw,rwl,rw2,rwz,sr2,srf,srw,tif,tiff,x3f';
                    break;
                case 'meta':
                    $googleDriveDir = Auth::user()->google_drive_dir_metas;
                    $googleDriveDirDesc = 'Metadata Directory';
                    $allowedMimeTypes = 'xmp,txt';
                    break;
                default:
                    throw new Exception('Unhandled file type: ' . $data['other_type']);
            }
            Validator::validate($data, [
                'file' => "mimes:{$allowedMimeTypes}",
            ]);

            // Check if the Google Drive dir has been initialized
            if (strlen($googleDriveDir) <= 0) {
                throw ValidationException::withMessages([
                    'google_drive' => 'You must set the "' . $googleDriveDirDesc . '" on the "Profile" page before uploading edits.',
                ]);
            }

            // Create the extension and filename
            $guid = Str::uuid();
            $extension = null;
            if (strpos($data['file']->getClientOriginalName(), '.') >= 0) {
                $filenameParts = explode('.', $data['file']->getClientOriginalName());
                $extension = end($filenameParts);
            }
            if (strlen($extension) <= 0) {
                $extension = 'xmp';
            }
            $filename = "{$guid}.{$extension}";

            // Upload image to Google Drive
            $google = new GoogleDrive;
            if (!$google->isAuthed()) {
                throw ValidationException::withMessages([
                    'google_drive' => 'Invalid Google Drive Session',
                ]);
            }
            $destinationDir = join(DIRECTORY_SEPARATOR, [trim($googleDriveDir, '/'), $photo->guid]);
            $googleDir = $google->mkdirs($destinationDir);
            $googleFileID = $google->upload(
                $googleDir,
                $filename,
                $data['file']->getMimeType(),
                $data['file']->getPathname()
            );

            // Create the database record
            $file = new PhotographOtherFile;
            $file->other_type = $data['other_type'];
            $file->filename = $filename;
            $file->filetype = $extension;
            $file->google_drive_file_id = $googleFileID;
            $file->user()->associate(Auth::user());
            $file->photograph()->associate($photo);

            // Read the exif data (metadata)
            $exif = ExifData::read($data['file']->getPathname());
            $file->camera = $exif->camera();
            $file->lens = null;
            $file->filter = null;
            $file->focal_length = $exif->focalLength();
            $file->exposure_time = $exif->exposureTime();
            $file->aperture = $exif->aperture();
            $file->iso = $exif->iso();

            // Save the record
            $file->saveOrFail();

            return response()->json($file, 200);

        } catch (ValidationException $e) {
            $message = 'Form Errors:';
            foreach ($e->validator->getMessageBag()->getMessages() as $field => $error) {
                $errorMessage = implode(', ', $error);
                $message .= " {$field} - {$errorMessage}";
            }
            return response()->make($message, 422);

        } catch (Throwable $e) {
            return response()->make("Error: {$e->getMessage()}", 419);
        }
    }

    /**
     * Downloads a file from Google Drive and outputs it in the response
     *
     * @param $googleFileID
     * @param $filename
     * @return \Illuminate\Http\Response
     * @throws ValidationException
     */
    private function downloadGoogleDriveFile($fileID, $filename)
    {
        $google = new GoogleDrive;
        if (!$google->isAuthed()) {
            throw ValidationException::withMessages([
                'google_drive' => 'Invalid Google Drive Session',
            ]);
        }
        if (strlen($fileID) <= 0) {
            throw ValidationException::withMessages([
                'google_drive_file_id' => 'The file has not been uploaded to Google Drive yet.',
            ]);
        }

        try {
            return response()->make($google->download($fileID), 200, [
                'Content-type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'google_drive' => 'Unable to download file from Google Drive',
            ]);
        }
    }

    /**
     * Downloads the edited (full-size) photo from Google Drive.
     *
     * @param Photograph $photo
     * @return
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws ValidationException
     */
    public function downloadPhotoEdit(Photograph $photo)
    {
        return $this->downloadGoogleDriveFile($photo->google_drive_file_id, "{$photo->name}.jpg");
    }

    /**
     * Downloads a Metadata or RAW file from Google Drive.
     *
     * @param Photograph $photo
     * @param PhotographOtherFile $file
     * @return \Illuminate\Http\Response
     * @throws ValidationException
     */
    public function downloadPhotoOtherFile(PhotographOtherFile $file)
    {
        return $this->downloadGoogleDriveFile($file->google_drive_file_id, $file->filename);
    }

    /**
     * Updates the meta information on an attached file.
     *
     * @param Request $request
     * @param PhotographOtherFile $file
     * @return \Illuminate\Http\RedirectResponse
     * @throws Throwable
     * @throws ValidationException
     */
    public function updateOther(Request $request, PhotographOtherFile $file)
    {
        $data = $this->validate($request, [
            'camera' => 'present|string|between:1,255|nullable',
            'lens' => 'present|string|between:1,255|nullable',
            'filter' => 'present|string|between:1,255|nullable',
            'focal_length' => 'present|string|between:1,255|nullable',
            'exposure_time' => 'present|string|between:1,255|nullable',
            'aperture' => 'present|string|between:1,255|nullable',
            'iso' => 'present|string|between:1,255|nullable',
        ]);

        $file->fill($data);
        $file->saveOrFail();

        return redirect()->back()->with('status', 'File successfully saved.');
    }

    /**
     * Deletes an additional file.
     *
     * @param PhotographOtherFile $file
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function deleteOther(PhotographOtherFile $file)
    {
        $file->delete();
        return redirect()->back()->with('status', 'File successfully deleted.');
    }

    /**
     * Updates the links to this photo on linked social platforms.
     *
     * @param Request $request
     * @param Photograph $photo
     * @return \Illuminate\Http\RedirectResponse
     * @throws Throwable
     */
    public function updateSocialLinks(Request $request, Photograph $photo)
    {
        // Validation
        $data = $this->validate($request, [
            'instagram_url' => 'url|max:1000|nullable',
            'fineartamerica_url' => 'url|max:1000|nullable',
            'redbubble_url' => 'url|max:1000|nullable',
            'etsy_url' => 'url|max:1000|nullable',
            'ebay_url' => 'url|max:1000|nullable',
        ]);

        // Update the photo
        $photo->fill($data);
        $photo->saveOrFail();

        // Return to the previous page
        return redirect()->back()->with('status', 'Social link(s) successfully updated.');
    }
}
