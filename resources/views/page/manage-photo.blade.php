<?php
$googleOAuth = Auth::user()->googleDriveOauth()->latest()->first();
$hasGoogleAccess = $googleOAuth && !$googleOAuth->expires_at->isPast();
$tags = old('tags', json_decode($photo->tags, true));
$hasEditedPhoto = $photo->photographEdits()->count() > 0;
$editedPhotoURL = $hasEditedPhoto ? $photo->photographEdits('thumb')->first()->imageURL() : '';
?>

@extends('layout.main', ['navLogo' => true])

@section('content')

    <div class="bg-white bg-opacity-75 backdrop-blur-10 rounded-lg shadow-md w-full my-10 p-10">

        <!-- Header -->
        <div class="flex flex-row flex-no-wrap items-center justify-start mb-10">

            <h1 class="text-2xl">Manage Photo: <span class="font-bold font-mono">{{ $photo->guid }}</span></h1>

        </div>

        <!-- Top Section -->
        <div class="flex flex-row flex-wrap justify-start items-start">

            <!-- Left Column -->
            <div class="w-full md:w-1/2 md:pr-10 md:border-r border-gray-700">

                <!-- Profile Info Form -->
                <form method="POST" action="{{ route('profile') }}">
                    @csrf

                    <!-- Identifier -->
                    <div class="mb-4">
                        <label class="block text-gray-900 text-sm font-bold mb-2" for="guid">
                            Identifier
                        </label>
                        <input type="text" name="guid" placeholder="Identifier"
                               value="{{ old('guid', $photo->guid) }}" readonly
                               class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 cursor-not-allowed opacity-50 leading-tight focus:outline-none focus:shadow-outline bg-white duration-200 ease-in-out">
                        @error('guid')
                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="block text-gray-900 text-sm font-bold mb-2" for="status">
                            Status
                        </label>
                        <div class="relative">
                            <select name="status"
                                    class="block appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                                <option value="active" {{ $photo->status === 'active' ? 'selected' : '' }}>
                                    Active Listing
                                </option>
                                <option value="inactive" {{ $photo->status === 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
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
                        @error('status')
                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Photo Name -->
                    <div class="mb-4">
                        <label class="block text-gray-900 text-sm font-bold mb-2" for="name">
                            Photo Name
                        </label>
                        <input type="text" name="name" placeholder="Photo Name"
                               value="{{ old('name', $photo->name) }}"
                               class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                        @error('name')
                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div class="mb-4">
                        <label class="block text-gray-900 text-sm font-bold mb-2" for="location">
                            Location
                        </label>
                        <input type="text" name="location" placeholder="Location"
                               value="{{ old('location', $photo->location) }}"
                               class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                        @error('location')
                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label class="block text-gray-900 text-sm font-bold mb-2" for="description">
                            Description
                        </label>
                        <textarea name="description" placeholder="Description"
                                  class="appearance-none border border-gray-600 rounded w-full h-24 py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">{{ old('description', $photo->description ?? '') }}</textarea>
                        @error('description')
                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tags -->
                    <div class="mb-10">
                        <label class="block text-gray-900 text-sm font-bold mb-1" for="tags-input">Tags (<span id="tags-count">0</span>)</label>

                        <div id="tag-input-container"></div>
                        <div id="tag-display-container" class="flex flex-wrap flex-row items-start justify-start"></div>

                        <input id="tags-input" type="text" placeholder="Tags&#8230; (press enter to add)"
                               class="appearance-none border border-gray-600 rounded w-full mt-1 py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                        @error('tags')
                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Save Button -->
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                        Save
                    </button>

                </form>

            </div>

            <!-- Right Column -->
            <div class="w-full md:w-1/2 md:pl-5 mt-10 md:mt-0">

                <!-- Edited Image -->
                <div class="mb-10">
                    <label class="block text-gray-900 text-sm font-bold mb-1" for="tags-input">Edited Image</label>

                    <!-- Preview -->
                    <img id="edited-photo" alt="Photo Edit" src="{{ $editedPhotoURL }}"
                         class="{{ $hasEditedPhoto ? 'block' : 'hidden' }} w-100 h-auto object-contain object-center"/>

                    <!-- Upload Form -->
                    <div class="{{ $hasEditedPhoto ? 'hidden' : '' }}">
                        <form id="my-awesome-dropzone" class="dropzone"
                              action="{{ route('photograph.upload-edit', $photo->id) }}">
                            @csrf
                            <input type="file" name="image"/>
                        </form>
                    </div>

                    <!-- Download Button -->
                    <!-- TODO: implement this -->
                    <button type="submit"
                            class="{{ $hasEditedPhoto ? '' : 'hidden' }} mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                        Download Full Res.
                    </button>
                </div>

            </div>
        </div>

    </div>

    <script type="text/javascript">
        window.addEventListener("load", function () {
            @foreach($tags as $tag)
            window.addNewPhotoTag('{!! $tag !!}');
            @endforeach
        });
    </script>

@endsection