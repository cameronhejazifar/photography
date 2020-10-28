<?php
$hasEditedPhoto = $photo->photographEdits()->count() > 0;
$editedPhoto = $hasEditedPhoto ? $photo->photographEdits('thumb')->first() : null;
$editedPhotoURL = $hasEditedPhoto ? $editedPhoto->imageURL() : '';
?>

@extends('layout.popup')

@section('content')

    <form id="flickr-post-form" method="POST" action="{{ route('flickr.post.submit', $photo->id) }}"
          class="w-full p-10">
        @csrf

        <!-- Image -->
        <img alt="Photo" src="{{ $editedPhotoURL }}"
             class="block w-75 h-auto object-contain object-center mb-8"/>

        <!-- Title -->
        <div class="mb-3">
            <label class="block text-gray-900 text-sm font-bold mb-1" for="title">
                Title
            </label>
            <input type="text" name="title" placeholder="Title" value="{{ $photo->name }}"
                   class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
        </div>

        <!-- Location -->
        <div class="mb-3">
            <label class="block text-gray-900 text-sm font-bold mb-1" for="location">
                Location
            </label>
            <input type="text" name="location" placeholder="Location" value="{{ $photo->location }}"
                   class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label class="block text-gray-900 text-sm font-bold mb-1" for="description">
                Description
            </label>
            <textarea name="description" placeholder="Description"
                      class="appearance-none border border-gray-600 rounded w-full h-16 py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">{{ $photo->description }}</textarea>
        </div>

        <!-- Tags -->
        <div class="mb-3">
            <label class="block text-gray-900 text-sm font-bold mb-1" for="tags">
                Tags
            </label>
            <textarea name="tags" placeholder="Tags (comma separated)"
                      class="appearance-none border border-gray-600 rounded w-full h-16 py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">{{ implode(',', json_decode($photo->tags, true)) }}</textarea>
        </div>

        <!-- Visibility -->
        <div class="mb-8">
            <label class="block text-gray-900 text-sm font-bold mb-1" for="visibility">
                Visibility
            </label>
            <div class="flex flex-row flex-no-wrap items-center justify-start">
                <label for="is_public" class="flex flex-row flex-no-wrap items-center justify-start mr-5">
                    <input type="checkbox" id="is_public" name="is_public" checked value="yes"/>
                    <span class="ml-1">Public</span>
                </label>
                <label for="is_friend" class="flex flex-row flex-no-wrap items-center justify-start mr-5">
                    <input type="checkbox" id="is_friend" name="is_friend" checked value="yes"/>
                    <span class="ml-1">Friends</span>
                </label>
                <label for="is_family" class="flex flex-row flex-no-wrap items-center justify-start">
                    <input type="checkbox" id="is_family" name="is_family" checked value="yes"/>
                    <span class="ml-1">Family</span>
                </label>
            </div>
        </div>

        <!-- Save Button -->
        <button type="submit" id="flickr-form-submit"
                class="inline-flex flex-row justify-center items-center bg-pink-500 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
            <svg class="hidden animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Post to Flickr</span>
        </button>

        <!-- Errors -->
        <p id="flickr-form-errors" class="text-red-700 text-sm italic"></p>

    </form>

@endsection
