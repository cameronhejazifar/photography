@extends('layout.main', ['navLogo' => true])

@section('content')

    <div class="bg-white bg-opacity-75 backdrop-blur-10 rounded-lg shadow-md w-full my-10 p-10">

        <h1 class="text-2xl mb-10">New Photograph</h1>

        <div class="flex flex-row items-center justify-start">
            <a href="/" id="link-google-drive"
               class="inline-flex flex-col items-center justify-center py-4 px-8 bg-white border border-gray-600 rounded hover:border-gray-800 focus:bg-gray-200">
                <span class="text-sm">Click to re-authorize</span>
                <img class="w-48 h-auto mt-2" src="{{ asset('img/services/google-drive.svg') }}" alt="Google Drive™" title="Google Drive™"/>
            </a>
            <span id="googledrive-auth-success" class="hidden ml-5 p-2 bg-green-200 border border-green-900 text-green-900 text-sm rounded">
                Successfully authorized.
            </span>
            <span id="googledrive-auth-failure" class="hidden ml-5 p-2 bg-red-200 border border-red-900 text-red-900 text-sm rounded">
                Authorization failed.
            </span>
        </div>

    </div>

@endsection
