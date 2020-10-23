<?php

namespace App\Http\Controllers;

use App\Classes\ExifData;
use App\Classes\GoogleDrive;
use App\Models\Photograph;
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
        $this->middleware('auth')->except('uploadEdit');
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

        // Create the photo
        $photo = new Photograph($data);
        $photo->tags = json_encode($photo->tags);
        $photo->user()->associate(Auth::user());
        $photo->saveOrFail();

        // Return the response
        return redirect()->intended(route('photograph.manage', ['photo' => $photo->id]));
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
            $large->user()->associate(Auth::user());
            $large->photograph()->associate($photo);
            $large->storeImage($largeImage);
            $large->saveOrFail();
            $large->url = $large->imageURL();

            // Release memory
            $largeImage = null;
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
            return response()->make(json_encode(compact('large', 'thumb')), 201);

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
     * Downloads the edited (full-size) photo from Google Drive.
     *
     * @param Photograph $photo
     * @return
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws ValidationException
     */
    public function downloadPhotoEdit(Photograph $photo)
    {
        $google = new GoogleDrive;
        if (!$google->isAuthed()) {
            throw ValidationException::withMessages([
                'google_drive' => 'Invalid Google Drive Session',
            ]);
        }
        if (strlen($photo->google_drive_file_id) <= 0) {
            throw ValidationException::withMessages([
                'google_drive_file_id' => 'The file has not been uploaded to Google Drive yet.',
            ]);
        }

        try {
            return response()->make($google->download($photo->google_drive_file_id), 200, [
                'Content-type' => 'image/jpeg',
                'Content-Disposition' => 'attachment; filename="' . $photo->name . '.jpg"',
            ]);
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'google_drive' => 'Unable to download file from Google Drive',
            ]);
        }
    }

    /**
     * Downloads a Metadata or RAW file from Google Drive.
     *
     * @param Photograph $photo
     * @param PhotographOtherFile $file
     * @return \Illuminate\Http\Response
     * @throws ValidationException
     */
    public function downloadPhotoOtherFile(PhotographOtherFile $file) {
        $google = new GoogleDrive;
        if (!$google->isAuthed()) {
            throw ValidationException::withMessages([
                'google_drive' => 'Invalid Google Drive Session',
            ]);
        }
        if (strlen($file->google_drive_file_id) <= 0) {
            throw ValidationException::withMessages([
                'google_drive_file_id' => 'The file has not been uploaded to Google Drive yet.',
            ]);
        }

        try {
            return response()->make($google->download($file->google_drive_file_id), 200, [
                'Content-type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="' . $file->filename . '"',
            ]);
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'google_drive' => 'Unable to download file from Google Drive',
            ]);
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
}
