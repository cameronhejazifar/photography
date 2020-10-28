<?php

namespace App\Http\Controllers;

use App\Models\Photograph;

class InstagramController extends Controller
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
     * Generates the information that is formatted properly for Instagram.
     *
     * @param Photograph $photo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function generatePost(Photograph $photo)
    {
        return view('popup.instagram-post', compact('photo'));
    }
}
