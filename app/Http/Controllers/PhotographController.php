<?php

namespace App\Http\Controllers;

use App\Models\Photograph;
use App\Models\PhotographEdit;
use Auth;
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
        try {
            $data = $this->validate($request, [
                'image' => 'required|image|max:131072',
            ]);

            // TODO: copy original (unscaled) image to Google Drive

            // Create the large image
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
            $thumbImage->resize(768, 768, function (Constraint $constraint) {
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

            // Return the response
            return response()->make(json_encode(compact('large', 'thumb')), 201);

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
