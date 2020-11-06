@extends('layout.main', ['navLogo' => true])

@section('content')

    <!-- Collection List -->
    <div id="collection-list" class="horizontal-scroller w-full m-3 flex overflow-x-auto"
         data-collection-endpoint="{{ route('browse.collections') }}">
    </div>

    <!-- Photo List -->
    <div id="photo-list" class="flex flex-row flex-wrap items-center justify-center mb-1"
         data-initial-query="{{ route('browse.photographs') }}">

        <!-- Initial Loading Spinner -->
        <svg class="animate-spin m-10 h-12 w-12 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>

    <!-- Load More Button -->
    <div id="load-more-button-container" class="hidden text-center my-4">
        <button type="button" id="load-more-button"
           class="inline-flex flex-row justify-center items-center bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out disabled:opacity-50">
            <svg class="hidden animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Load More</span>
        </button>
    </div>

@endsection
