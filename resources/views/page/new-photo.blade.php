@extends('layout.main', ['navLogo' => true])

@section('content')

    <div class="bg-white bg-opacity-75 backdrop-blur-10 rounded-lg shadow-md w-full my-10 p-10">

        <h1 class="text-2xl mb-10">New Photograph</h1>

        <a href="/"
           class="inline-flex flex-col items-center justify-center py-4 px-8 bg-white border border-gray-600 rounded hover:border-gray-800 focus:bg-gray-200">
            <span class="text-sm">Click to authorize access</span>
            <img class="w-48 h-auto mt-2" src="{{ asset('img/services/google-drive.svg') }}" alt="Google Drive™" title="Google Drive™"/>
        </a>

    </div>

@endsection
