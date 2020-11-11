<?php

namespace App\Http\Controllers;

use App\Models\Photograph;
use App\Models\PhotographCollection;
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
     * Show the photograph list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('page.view-photo-list');
    }

    /**
     * Show an individual photograph.
     *
     * @param Photograph $photo
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Photograph $photo)
    {
        if ($photo->status !== 'active' || $photo->photographEdits()->count() <= 0) {
            abort(404);
        }
        return view('page.view-photo', compact('photo'));
    }

    /**
     * Query collections with active photographs.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCollections()
    {
        $collections = (new PhotographCollection)->newQuery()
            ->select('title')
            ->whereHas('photograph', function($q) {
                $q->where('status', '=', 'active');
            })
            ->orderBy('title', 'asc')
            ->distinct()
            ->get();
        $results = [];
        foreach ($collections as &$collection) {
            $photoIDs = PhotographCollection::where('title', '=', $collection->title)->pluck('photograph_id')->toArray();
            $edits = (new PhotographEdit)->newQuery()
                ->whereIn('photograph_id', $photoIDs)
                ->whereHas('photograph', function($q) {
                    $q->where('status', '=', 'active');
                })
                ->where('scaled_size', '=', 'thumb')
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->with('user')
                ->get();
            if ($edits->count() > 0) {
                $thumbs = [];
                foreach ($edits as $edit) {
                    $thumbs[] = $edit->imageURL();
                }
                $collection->created_by = $edits->get(0)->user;
                $collection->thumbnail_urls = $thumbs;
                $collection->browse_url = route('browse', ['collection' => $collection->title]);
                $results[] = $collection;
            }
        }
        return response()->json($results);
    }

    /**
     * Query photographs.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPhotographs(Request $request)
    {
        $collectionFilter = $request->input('collection');
        $query = (new Photograph)->newQuery()
            ->has('photographEdits')
            ->where('status', '=', 'active')
            ->orderBy('created_at', 'desc')
            ->with('photographEdits', function($q) {
                $q->where('scaled_size', '=', 'thumb');
            });
        if (strlen($collectionFilter) > 0) {
            $query->whereHas('photographCollections', function($q) use($collectionFilter) {
                $q->where('title', '=', $collectionFilter);
            });
        }
        $results = $query->paginate(20);
        if ($collectionFilter) {
            $results->appends('collection', $collectionFilter);
        }
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
