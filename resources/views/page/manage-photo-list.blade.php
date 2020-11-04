<?php
$collectionQuery = request('collection', null);
/** @var \Illuminate\Pagination\LengthAwarePaginator $photos */
$query = Auth::user()->photographs()->orderBy('created_at', 'desc');
if ($collectionQuery) {
    $query = $query->whereIn(
        'id',
        Auth::user()->photographCollections()
        ->where('title', '=', $collectionQuery)
        ->pluck('photograph_id')
        ->toArray()
    );
}
$photos = $query->paginate(1);
if ($collectionQuery) {
    $photos->appends('collection', $collectionQuery);
}
$hasPrevPage = !!($photos->previousPageUrl());
$hasNextPage = !!($photos->nextPageUrl());
$collections = Auth::user()->photographCollections()->select('title')->distinct()->get();
?>

@extends('layout.main', ['navLogo' => true])

@section('content')

    <div class="bg-white bg-opacity-75 backdrop-blur-10 rounded-lg shadow-md w-full my-10 p-10">

        <!-- Header -->
        <div class="flex flex-row flex-no-wrap items-center justify-between mb-10">

            <h1 class="text-2xl">Manage Photographs</h1>

        </div>

        <div class="mb-6">
            <label class="block text-gray-900 text-sm font-bold mb-1" for="collection-selector">
                Collection Filter
            </label>

            <div class="inline-block relative mr-2">
                <select id="collection-selector"
                        class="block appearance-none border border-gray-600 rounded w-full py-2 pl-3 pr-8 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                    <option value="{{ route('photograph') }}">Show All Collections</option>
                    @foreach($collections as $collection)
                        <option value="{{ route('photograph', ['collection' => $collection->title]) }}"
                                {{ $collection->title === $collectionQuery ? 'selected' : '' }}>
                            {{ $collection->title }}
                        </option>
                    @endforeach
                </select>
                <div
                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 20 20">
                        <path
                            d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                    </svg>
                </div>
            </div>
        </div>

        <p class="text-sm text-gray-700">
            Displaying
            {{ (($photos->currentPage() - 1) * $photos->perPage()) + 1 }}-{{ (($photos->currentPage() - 1) * $photos->perPage()) + $photos->count() }}
            of
            {{ $photos->total() }}
            photos.
        </p>

        <!-- Photo List -->
        <div class="flex flex-row flex-wrap items-center justify-start">
            @foreach ($photos as $photo)

                <?php
                    $edit = $photo->photographEdits('thumb')->first();
                    $thumbURL = $edit ? $edit->imageURL() : asset('img/photograph.png');
                ?>

                <!-- Individual Photo -->
                <div class="p-2 w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5">
                    <a href="{{ route('photograph.manage', $photo) }}"
                       class="block p-4 border border-gray-600 rounded hover:border-gray-700 hover:bg-gray-600 hover:bg-opacity-25">

                        <!-- Location -->
                        <div class="flex flex-row items-center justify-start mb-3 text-md text-gray-800">
                            <svg class="w-4 h-4 mr-2 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 0c-4.198 0-8 3.403-8 7.602 0 4.198 3.469 9.21 8 16.398 4.531-7.188 8-12.2 8-16.398 0-4.199-3.801-7.602-8-7.602zm0 11c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3z"/></svg>
                            <span class="truncate">{{ $photo->location }}</span>
                        </div>

                        <!-- Image -->
                        <img class="w-full h-auto mb-3 object-contain shadow-lg"
                             alt="Photo {{ $photo->id }}" title="Photo {{ $photo->id }}"
                             src="{{ $thumbURL }}"/>

                        <!-- Date -->
                        <span class="block truncate text-xs text-gray-700">
                            {{ $photo->created_at->format('F j, Y') }}
                        </span>

                        <!-- Name -->
                        <span class="block truncate text-lg font-bold text-gray-800">{{ $photo->name }}</span>

                        <!-- Description -->
                        <span class="block w-full text-sm text-gray-700 leading-snug h-10 overflow-hidden">
                            {{ $photo->description }}
                        </span>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex flex-row flex-no-wrap items-center justify-center mx-auto mt-4">

            <!-- Previous Page -->
            <a href="{{ $photos->previousPageUrl() }}"
               class="bg-gray-300 text-gray-800 border-gray-500 font-bold py-2 px-4 rounded-l border-r {{ $hasPrevPage ? 'hover:bg-gray-400' : 'cursor-not-allowed text-opacity-25' }}">
                &larr;
            </a>

            <!-- Page Selector Dropdown -->
            <div class="relative">
                <select id="page-selector"
                        class="block w-full appearance-none bg-gray-300 text-gray-800 border-gray-500 font-bold py-2 pl-4 pr-8 border-r">
                    @for($page = 1; $page <= $photos->lastPage(); $page++)
                        <option value="{{ $photos->url($page) }}" {{ $page === $photos->currentPage() ? 'selected' : '' }}>
                            Page {{ $page }}
                        </option>
                    @endfor
                </select>
                <div
                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 20 20">
                        <path
                            d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                    </svg>
                </div>
            </div>

            <!-- Next Page -->
            <a href="{{ $photos->nextPageUrl() }}"
               class="bg-gray-300 text-gray-800 border-gray-500 font-bold py-2 px-4 rounded-r {{ $hasNextPage ? 'hover:bg-gray-400' : 'cursor-not-allowed text-opacity-25' }}">
                &rarr;
            </a>
        </div>

    </div>

@endsection
