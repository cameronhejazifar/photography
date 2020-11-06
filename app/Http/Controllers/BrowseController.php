<?php

namespace App\Http\Controllers;

use App\Models\Photograph;
use App\Models\PhotographEdit;
use Illuminate\Http\Request;

class BrowseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('page.view-photo-list');
    }

    /**
     * Query photographs.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPhotographs(Request $request)
    {
        $query = (new Photograph)->newQuery()
            ->has('photographEdits')
            ->where('status', '=', 'active')
            ->orderBy('created_at', 'desc')
            ->with('photographEdits', function($q) {
                $q->where('scaled_size', '=', 'thumb');
            });
        $results = $query->paginate(1);
        /** @var Photograph $photo */
        foreach ($results as &$photo) {
            foreach ($photo->photographEdits as &$edit) {
                $edit->image_url = $edit->imageURL();
                $photo->photograph_url = route('browse.photograph', $photo->id);
            }
        }
        return response()->json($results);
    }
}
