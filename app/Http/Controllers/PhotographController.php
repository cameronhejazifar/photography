<?php

namespace App\Http\Controllers;

use App\Classes\GoogleDrive;
use App\Models\GoogleDriveOauth;
use App\Models\Photograph;
use App\Models\PhotographEdit;
use Auth;
use DB;
use Google_Service_Drive;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Image;
use Intervention\Image\Constraint;
use Throwable;

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
}
