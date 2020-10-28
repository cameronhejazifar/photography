<?php
$googleOAuth = Auth::user()->googleDriveOauth()->latest()->first();
$hasGoogleAccess = $googleOAuth && !$googleOAuth->expires_at->isPast();
$tags = old('tags', json_decode($photo->tags, true));
$hasEditedPhoto = $photo->photographEdits()->count() > 0;
$editedPhoto = $hasEditedPhoto ? $photo->photographEdits('thumb')->first() : null;
$editedPhotoURL = $hasEditedPhoto ? $editedPhoto->imageURL() : '';
$otherFiles = $photo->photographOtherFiles()->orderBy('other_type')->orderBy('filename')->get();
?>

@extends('layout.main', ['navLogo' => true])

@section('content')

    <div class="bg-white bg-opacity-75 backdrop-blur-10 rounded-lg shadow-md w-full my-10 p-10">

        <!-- Header -->
        <div class="flex flex-row flex-no-wrap items-center justify-between mb-10">

            <h1 class="text-2xl">Manage Photo</span></h1>

            <div>
                @if($photo->status === 'active')
                <button type="button" id="unpublish-button"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                    Un-Publish from Site
                </button>
                @endif

                @if($photo->status === 'inactive')
                <button type="button" id="publish-button"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                    Publish to Site
                </button>
                @endif
            </div>

        </div>

        @if (session('status'))
            <p class="mb-5 p-2 bg-green-200 border border-green-900 text-green-900 text-sm rounded">
                {{ session('status') }}
            </p>
        @endif

        <!-- Top Section -->
        <div class="flex flex-row flex-wrap justify-start items-start">

            <!-- Left Column -->
            <div class="w-full md:w-1/2 md:pr-10 md:border-r border-gray-700">

                <!-- Profile Info Form -->
                <form id="profile-info-form" method="POST" action="{{ route('photograph.update', $photo->id) }}">
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
            <div class="w-full md:w-1/2 md:pl-10 mt-10 md:mt-0">

                <span class="block md:hidden w-11/12 h-px mb-10 mx-auto bg-gray-700"></span>

                <!-- Edited Image -->
                <div class="mb-10">
                    <label class="block text-gray-900 text-sm font-bold mb-1" for="tags-input">Edited Image</label>

                    <!-- Preview -->
                    @if($hasEditedPhoto)
                        <img id="edited-photo" alt="Photo Edit" src="{{ $editedPhotoURL }}"
                             class="block w-100 h-auto object-contain object-center"/>
                    @endif

                    <!-- Google Drive Authorization -->
                    <div class="mt-5 flex flex-row items-center justify-start">
                        <a href="/" id="link-google-drive"
                           class="inline-flex flex-col items-center justify-center py-4 px-8 bg-white border border-gray-600 rounded hover:border-gray-800 focus:bg-gray-200">
                            <img class="w-48 h-auto mb-2" src="{{ asset('img/services/google-drive.svg') }}"
                                 alt="Google Drive™" title="Google Drive™"/>
                            <span class="text-sm">Click to re-authorize</span>
                        </a>
                        <span id="googledrive-auth-success"
                              class="{{ $hasGoogleAccess ? '' : 'hidden' }} ml-5 p-2 bg-green-200 border border-green-900 text-green-900 text-sm text-center rounded">
                            Successfully authorized.
                        </span>
                        <span id="googledrive-auth-failure"
                              class="{{ $hasGoogleAccess ? 'hidden' : '' }} ml-5 p-2 bg-red-200 border border-red-900 text-red-900 text-sm text-center rounded">
                            Authorization failed.
                        </span>
                    </div>

                    <!-- Download Button -->
                    @if($hasEditedPhoto)
                        <div id="download-edit-container" class="mt-4">
                            <a id="download-edit" href="{{ route('photograph.download', $photo->id) }}"
                               class="{{ $hasGoogleAccess ? '' : 'disabled cursor-not-allowed' }} bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                                Download Full Res.
                            </a>
                            @error('google_drive')
                            <p class="text-red-700 text-sm italic">{{ $message }}</p>
                            @enderror
                            @error('google_drive_file_id')
                            <p class="text-red-700 text-sm italic">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    @if(!$hasEditedPhoto)
                        <!-- Upload Form -->
                        <div id="upload-edit-container" class="mt-4 {{ $hasGoogleAccess ? '' : 'hidden' }}">
                            <form id="upload-edit" class="dropzone"
                                  action="{{ route('photograph.upload-edit', $photo->id) }}">
                                @csrf
                                <input type="file" name="image"/>
                                <svg class="dz-spinner animate-spin absolute -ml-2 -my-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <span class="block w-11/12 h-px my-10 mx-auto bg-gray-700"></span>

        <!-- Bottom Section -->
        <div class="flex flex-row flex-wrap justify-start items-start">

            <!-- Left Column -->
            <div class="w-full md:w-1/2 md:pr-10 md:border-r border-gray-700">

                <!-- Raw Files -->
                @if($otherFiles->count() > 0)
                    <div class="mb-4">
                        <label class="block text-gray-900 text-sm font-bold mb-2" for="other-files">
                            Other Files
                        </label>

                        @foreach($otherFiles as $otherFile)
                            <form method="POST" action="{{ route('photograph.update-other', $otherFile->id) }}"
                                  class="flex flex-col justify-center items-center border border-gray-600 rounded p-2 mb-5">
                                @csrf

                                <div class="flex flex-row flex-wrap justify-center items-center w-full">

                                    <!-- Filename -->
                                    <div class="text-sm text-gray-700 font-bold w-3/4 text-left md:pr-1 mb-1 md:mb-0">
                                        {{ $otherFile->filename }}
                                    </div>

                                    <div class="flex flex-row flex-no-wrap justify-end items-center w-1/4 pl-1">

                                        <!-- File Type -->
                                        <span class="text-xs text-blue-600 font-bold uppercase">
                                            {{ $otherFile->other_type }}
                                        </span>

                                        <span class="w-px h-2 bg-gray-600 mx-3"></span>

                                        <!-- Download Button -->
                                        <a href="{{ route('photograph.download-other', $otherFile->id) }}"
                                           class="appearance-none text-gray-700 p-1">
                                            <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/></svg>
                                        </a>
                                    </div>
                                </div>

                                <span class="block w-full h-px my-1 mx-auto bg-gray-600"></span>

                                <div class="flex flex-row flex-wrap justify-center items-center w-full mb-1">

                                    <!-- Camera -->
                                    <div class="text-sm text-gray-700 w-full md:w-1/2 md:pr-1 mb-1 md:mb-0">
                                        <label class="block text-gray-700 text-xs">
                                            Camera
                                        </label>
                                        <input type="text" name="camera" placeholder="Camera"
                                               value="{{ old('camera', $otherFile->camera) }}"
                                               class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                                        @error('camera')
                                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Lens -->
                                    <div class="text-xs text-gray-700 w-full md:w-1/2 md:pl-1">
                                        <label class="block text-gray-700 text-xs">
                                            Lens
                                        </label>
                                        <input type="text" name="lens" placeholder="Lens"
                                               value="{{ old('lens', $otherFile->lens) }}"
                                               class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                                        @error('lens')
                                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="flex flex-row flex-wrap justify-center items-center w-full mb-1">

                                    <!-- Filter -->
                                    <div class="text-sm text-gray-700 w-full md:w-1/2 md:pr-1 mb-1 md:mb-0">
                                        <label class="block text-gray-700 text-xs">
                                            Filter
                                        </label>
                                        <input type="text" name="filter" placeholder="Filter"
                                               value="{{ old('filter', $otherFile->filter) }}"
                                               class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                                        @error('filter')
                                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Focal Length -->
                                    <div class="text-xs text-gray-700 w-full md:w-1/2 md:pl-1">
                                        <label class="block text-gray-700 text-xs">
                                            Focal Length
                                        </label>
                                        <input type="text" name="focal_length" placeholder="Focal Length"
                                               value="{{ old('focal_length', $otherFile->focal_length) }}"
                                               class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                                        @error('focal_length')
                                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="flex flex-row flex-wrap justify-center items-center w-full mb-1">

                                    <!-- Exposure Time -->
                                    <div class="text-sm text-gray-700 w-full md:w-1/2 md:pr-1 mb-1 md:mb-0">
                                        <label class="block text-gray-700 text-xs">
                                            Exposure Time
                                        </label>
                                        <input type="text" name="exposure_time" placeholder="Exposure Time"
                                               value="{{ old('exposure_time', $otherFile->exposure_time) }}"
                                               class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                                        @error('exposure_time')
                                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Aperture -->
                                    <div class="text-xs text-gray-700 w-full md:w-1/2 md:pl-1">
                                        <label class="block text-gray-700 text-xs">
                                            Aperture
                                        </label>
                                        <input type="text" name="aperture" placeholder="Aperture"
                                               value="{{ old('aperture', $otherFile->aperture) }}"
                                               class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                                        @error('aperture')
                                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="flex flex-row flex-wrap justify-center items-center w-full mb-1">

                                    <!-- ISO -->
                                    <div class="text-sm text-gray-700 w-full md:w-1/2 md:pr-1 mb-1 md:mb-0">
                                        <label class="block text-gray-700 text-xs">
                                            ISO
                                        </label>
                                        <input type="text" name="iso" placeholder="ISO"
                                               value="{{ old('iso', $otherFile->iso) }}"
                                               class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                                        @error('iso')
                                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="text-xs text-gray-700 w-full md:w-1/2 md:pl-1 text-right">
                                        <label class="block text-gray-700 text-xs">&nbsp;</label>
                                        <div class="flex flex-row flex-no-wrap items-center justify-end">
                                            <!-- Save Button -->
                                            <button type="submit"
                                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                                                Save Exif Info
                                            </button>
                                            <!-- Delete Button -->
                                            <a href="{{ route('photograph.delete-other', $otherFile->id) }}"
                                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 ml-1 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                                                Delete File
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        @endforeach
                    </div>
                @endif

                <span class="block w-11/12 h-px my-10 mx-auto bg-gray-700"></span>

                <!-- Upload Raw File -->
                <div class="mb-4">
                    <label class="block text-gray-900 text-sm font-bold mb-2" for="other-files">
                        Upload Other Files
                    </label>

                    <div class="flex flex-no-wrap flex-col justify-center items-center">

                        <!-- File Type -->
                        <div class="relative w-full">
                            <select id="upload-raw-type"
                                    class="flex-grow-0 block appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                                <option selected disabled value="">Select an Option</option>
                                <option value="raw">
                                    Raw File (RAW, NEF, etc.)
                                </option>
                                <option value="meta">
                                    Meta/Lightroom File (.XMP, etc.)
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

                        <!-- Upload Zone -->
                        <form id="upload-raw" class="dropzone mt-2"
                              action="{{ route('photograph.upload-other', $photo->id) }}">
                            @csrf
                            <input type="hidden" name="other_type"/>
                            <input type="file" name="file"/>
                            <svg class="dz-spinner animate-spin absolute -ml-2 -my-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </form>
                    </div>
                </div>

            </div>

            <!-- Right Column -->
            <div class="w-full md:w-1/2 md:pl-10 mt-10 md:mt-0">

                <!-- Social / Monetization -->

                <!-- Flickr -->
                @if($hasEditedPhoto)
                    <div class="flex flex-no-wrap flex-row items-center justify-start">
                        <a id="post-to-flickr"
                           href="{{ route('flickr.oauth', ['next_url' => route('flickr.post', $photo->id)]) }}"
                           class="inline-flex flex-row justify-center items-center bg-pink-500 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                            <img class="w-5 h-5 mr-2" src="{{ asset('img/footer/flickr.png') }}" alt="Flickr"/>
                            <span>Post to Flickr</span>
                        </a>
                        <span id="flickr-post-success"
                              class="{{ $photo->flickrPosts()->count() > 0 ? '' : 'hidden' }} p-2 ml-5 bg-green-200 border border-green-900 text-green-900 text-sm text-center rounded">
                            Successfully posted to Flickr.
                        </span>
                        <span id="flickr-post-failure"
                              class="hidden ml-5 p-2 bg-red-200 border border-red-900 text-red-900 text-sm text-center rounded">
                            Post to Flickr failed.
                        </span>
                    </div>
                @endif

                <!-- TODO: set prices here?? or should that just reside in redbubble/fineartamerica? -->
                <!-- TODO: flickr -->
                <!-- TODO: instagram -->
                <!-- TODO: web (publish / unpublish button) -->
                <!-- TODO: redbubble -->
                <!-- TODO: fineartamerica -->
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
