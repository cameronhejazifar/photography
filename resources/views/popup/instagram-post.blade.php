<?php
$hasEditedPhoto = $photo->photographEdits()->count() > 0;
$editedPhoto = $hasEditedPhoto ? $photo->photographEdits('thumb')->first() : null;
$editedPhotoURL = $hasEditedPhoto ? $editedPhoto->imageURL() : '';
$instagramDescription = $photo->generateInstagramText();
?>

@extends('layout.popup')

@section('content')

    <div id="flickr-post-form" class="w-full p-5 flex-grow flex flex-col items-center justify-start">

        @if(strlen($photo->location) > 0)
            <!-- Location -->
            <div class="w-full mb-4 flex flex-row items-center justify-start text-gray-700">
                <svg class="w-4 h-4 mr-1 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 0c-4.198 0-8 3.403-8 7.602 0 4.198 3.469 9.21 8 16.398 4.531-7.188 8-12.2 8-16.398 0-4.199-3.801-7.602-8-7.602zm0 11c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3z"/></svg>
                {{ $photo->location }}
            </div>
        @endif

        <!-- Image -->
        <img alt="Photo" src="{{ $editedPhotoURL }}"
             class="block w-75 h-auto object-contain object-center mb-4"/>

        <!-- Description -->
        <textarea id="instagram-description" placeholder="Instagram Description"
                  class="w-full flex-grow appearance-none border border-gray-600 rounded py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">{{ $instagramDescription }}</textarea>

        <!-- Action Buttons -->
        <div class="w-full mt-4 flex flex-row items-center justify-center">
            <button type="button" id="close-instagram-post"
                    class="mr-2 w-1/2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                Close Window
            </button>
            <button type="button" id="copy-text-button"
                    class="ml-2 w-1/2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                Copy Text
            </button>
        </div>
    </div>

@endsection
