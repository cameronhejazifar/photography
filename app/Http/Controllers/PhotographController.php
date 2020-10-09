<?php

namespace App\Http\Controllers;

use App\Models\Photograph;
use Auth;
use Illuminate\Http\Request;

class PhotographController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showEditPhotographForm(Photograph $photo)
    {
        return view('page.edit-photo', compact('photo'));
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
            'name' => 'required|string|between:1,255',
            'location' => 'required|string|between:1,255',
            'description' => 'required|string|between:1,2000',
            'tags' => 'required|array|between:1,30',
            'tags.*' => 'required|string|between:1,65',
        ]);

        // Create the photo
        $photo = new Photograph($data);
        $photo->tags = json_encode($photo->tags);
        $photo->active = true;
        $photo->user()->associate(Auth::user());
        $photo->saveOrFail();

        // Return the response
        return redirect()->intended(route('photograph.edit', ['photo' => $photo->id]));
    }
}
