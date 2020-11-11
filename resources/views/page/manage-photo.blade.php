<?php
$checklists = $photo->photographChecklists;
$photoCollections = $photo->photographCollections;
$userCollections = Auth::user()->photographCollections()->select('title')->distinct()->get();

$googleOAuth = Auth::user()->googleDriveOauth()->latest()->first();
$hasGoogleAccess = $googleOAuth && !$googleOAuth->expires_at->isPast();

$flickrOAuth = Auth::user()->flickrOauth()->latest()->first();
$hasFlickrOAuth = $flickrOAuth && strlen($flickrOAuth->access_token) > 0;

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

            <h1 class="text-2xl">Manage Photo</h1>

            <div>
                <!-- Action Button Slot -->
            </div>

        </div>

        @if (session('status'))
            <p class="mb-5 p-2 bg-green-200 border border-green-900 text-green-900 text-sm rounded">
                {{ session('status') }}
            </p>
        @endif

        <!-- Top Section -->
        @if($checklists->count() > 0)
            <div class="flex flex-row flex-wrap justify-start items-start mb-8">
                <div class="w-full md:w-1/2 md:pr-10 md:border-r border-gray-700">

                    <h3 class="text-lg mb-1">Checklist</h3>

                    <ul class="list-none">
                        @foreach($checklists as $checklist)
                            <li class="mt-1">
                                <label for="checklist-{{ $checklist->id }}"
                                       class="inline-flex flex-row flex-no-wrap items-center justify-start text-sm text-gray-900 leading-5">
                                    @csrf
                                    <input type="checkbox" id="checklist-{{ $checklist->id }}"
                                           data-method="POST"
                                           data-action="{{ route('photograph.update-checklist', $checklist->id) }}"
                                           class="checklist-item mr-1" {{ $checklist->completed ? 'checked' : '' }}>
                                    <span>{{ $checklist->instruction }}</span>
                                    <svg class="hidden animate-spin ml-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </label>
                            </li>
                        @endforeach
                    </ul>

                </div>

                <div class="w-full md:w-1/2 md:pl-10 mt-10 md:mt-0">
                    <span class="block md:hidden w-11/12 h-px my-10 mx-auto bg-gray-700"></span>

                    <h3 class="text-lg mb-1">Collections</h3>

                    <div class="flex flex-row flex-wrap items-start justify-start">
                        @foreach($photoCollections as $collection)
                            <button type="submit" data-method="POST"
                                    data-action="{{ route('photograph.collection.delete', $collection->id) }}"
                                    class="delete-collection mr-1 mb-1 max-w-full inline-flex flex-row justify-center items-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                                <span>{{ $collection->title }}</span>
                                <svg class="hidden animate-spin -mr-1 ml-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <svg class="trash-icon fill-current w-5 h-5 ml-3 -mr-2" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg"><g><path d="m424 64h-88v-16c0-26.467-21.533-48-48-48h-64c-26.467 0-48 21.533-48 48v16h-88c-22.056 0-40 17.944-40 40v56c0 8.836 7.164 16 16 16h8.744l13.823 290.283c1.221 25.636 22.281 45.717 47.945 45.717h242.976c25.665 0 46.725-20.081 47.945-45.717l13.823-290.283h8.744c8.836 0 16-7.164 16-16v-56c0-22.056-17.944-40-40-40zm-216-16c0-8.822 7.178-16 16-16h64c8.822 0 16 7.178 16 16v16h-96zm-128 56c0-4.411 3.589-8 8-8h336c4.411 0 8 3.589 8 8v40c-4.931 0-331.567 0-352 0zm313.469 360.761c-.407 8.545-7.427 15.239-15.981 15.239h-242.976c-8.555 0-15.575-6.694-15.981-15.239l-13.751-288.761h302.44z"/><path d="m256 448c8.836 0 16-7.164 16-16v-208c0-8.836-7.164-16-16-16s-16 7.164-16 16v208c0 8.836 7.163 16 16 16z"/><path d="m336 448c8.836 0 16-7.164 16-16v-208c0-8.836-7.164-16-16-16s-16 7.164-16 16v208c0 8.836 7.163 16 16 16z"/><path d="m176 448c8.836 0 16-7.164 16-16v-208c0-8.836-7.164-16-16-16s-16 7.164-16 16v208c0 8.836 7.163 16 16 16z"/></g></svg>
                            </button>
                        @endforeach
                    </div>

                    <form id="add-collection-form" method="POST"
                          action="{{ route('photograph.collection', $photo->id) }}">
                        @csrf

                        <div class="flex flex-row flex-no-wrap items-center justify-center">
                            <div class="relative flex-grow mr-2">
                                <select name="title"
                                        class="block appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                                    <option selected disabled hidden value="">Select a Collection</option>
                                    <optgroup label="New Collection">
                                        <option value="_new">Create New</option>
                                    </optgroup>
                                    <optgroup label="Existing Collections">
                                        @foreach($userCollections as $collection)
                                            <option value="{{ $collection->title }}">{{ $collection->title }}</option>
                                        @endforeach
                                    </optgroup>
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
                            <button type="submit" id="add-collection-button"
                                    class="inline-flex flex-row justify-center items-center bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                                <span>Add</span>
                                <svg class="hidden animate-spin -mr-1 ml-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        </div>

                        @error('title')
                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                        @enderror

                    </form>

                </div>
            </div>
        @endif

        <span class="block w-11/12 h-px my-10 mx-auto bg-gray-700"></span>

        <!-- Middle Section -->
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
            <div class="w-full md:w-1/2 md:pr-10">

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

                    <span class="block w-11/12 h-px my-10 mx-auto bg-gray-700"></span>
                @endif

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
                                <option selected disabled hidden value="">Select an Option</option>
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
            <div class="w-full md:w-1/2 md:pl-10 mt-10 md:mt-0 md:border-l border-gray-700">

                <!-- Social / Monetization -->
                @if($hasEditedPhoto)
                    <span class="block md:hidden w-11/12 h-px my-10 mx-auto bg-gray-700"></span>

                    <!-- This Website -->
                    <div class="block">
                        <h3 class="text-lg mb-3">{{ config('app.name', 'Laravel') }} Photography</h3>

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

                    <span class="block w-11/12 h-px my-10 mx-auto bg-gray-700"></span>

                    <!-- Flickr -->
                    <div class="block">
                        <h3 class="text-lg mb-3">Flickr</h3>
                        <div class="flex flex-no-wrap flex-row items-center justify-start">
                            <a id="post-to-flickr"
                               href="{{ route('flickr.oauth', ['next_url' => route('flickr.post', $photo->id)]) }}"
                               class="inline-flex flex-row justify-center items-center bg-gradient-to-r from-blue-500 to-pink-600 hover:from-blue-600 hover:to-pink-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                                <img class="w-5 h-5 mr-2" src="{{ asset('img/footer/flickr.png') }}" alt="Flickr"/>
                                <span>Post to Flickr</span>
                            </a>
                            <div class="ml-4">
                                @if($hasFlickrOAuth)
                                    <a href="https://www.flickr.com/photos/{{ $flickrOAuth->flickr_nsid }}" target="_blank"
                                       class="my-2 inline-flex flex-no-wrap flex-row items-center text-pink-600 hover:text-pink-800 underline text-sm outline-none focus:shadow-outline">
                                        <svg class="w-6 h-6 mr-1 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" enable-background="new 0 0 50 50"><path d="M38.288 10.297l1.414 1.415-14.99 14.99-1.414-1.414z"/><path d="M40 20h-2v-8h-8v-2h10z"/><path d="M35 38H15c-1.7 0-3-1.3-3-3V15c0-1.7 1.3-3 3-3h11v2H15c-.6 0-1 .4-1 1v20c0 .6.4 1 1 1h20c.6 0 1-.4 1-1V24h2v11c0 1.7-1.3 3-3 3z"/></svg>
                                        View Flickr Photostream
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4">
                            <span id="flickr-post-success"
                                  class="{{ $photo->flickrPosts()->count() > 0 ? '' : 'hidden' }} p-2 bg-green-200 border border-green-900 text-green-900 text-sm text-center rounded">
                                Successfully posted to Flickr.
                            </span>
                            <span id="flickr-post-failure"
                                  class="hidden p-2 bg-red-200 border border-red-900 text-red-900 text-sm text-center rounded">
                                Post to Flickr failed.
                            </span>
                        </div>
                    </div>

                    <!-- Nixplay -->
                    @if(strlen(Auth::user()->nixplay_url) > 0)
                        <span class="block w-11/12 h-px my-10 mx-auto bg-gray-700"></span>

                        <div class="block">
                            <h3 class="text-lg mb-3">Nixplay</h3>

                            <a href="{{ Auth::user()->nixplay_url }}" target="_blank"
                               class="inline-flex flex-no-wrap flex-row items-center text-orange-700 hover:text-orange-900 underline text-sm outline-none focus:shadow-outline">
                                <svg class="w-6 h-6 mr-1 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" enable-background="new 0 0 50 50"><path d="M38.288 10.297l1.414 1.415-14.99 14.99-1.414-1.414z"/><path d="M40 20h-2v-8h-8v-2h10z"/><path d="M35 38H15c-1.7 0-3-1.3-3-3V15c0-1.7 1.3-3 3-3h11v2H15c-.6 0-1 .4-1 1v20c0 .6.4 1 1 1h20c.6 0 1-.4 1-1V24h2v11c0 1.7-1.3 3-3 3z"/></svg>
                                View Nixplay Album
                            </a>
                        </div>
                    @endif

                    <span class="block w-11/12 h-px my-10 mx-auto bg-gray-700"></span>

                    <!-- Instagram -->
                    <div class="block">
                        <h3 class="text-lg mb-3">Instagram</h3>
                        <a id="generate-instagram-post"
                           href="{{ route('instagram.post', $photo->id) }}"
                           class="inline-flex flex-row justify-center items-center bg-gradient-to-r from-yellow-500 via-pink-600 to-purple-600 hover:from-yellow-600 hover:via-pink-700 hover:to-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                            <img class="w-5 h-5 mr-2" src="{{ asset('img/footer/instagram.png') }}" alt="Instagram"/>
                            <span>Generate Instagram Post</span>
                        </a>
                        <label class="block text-gray-900 text-sm font-bold mt-2" for="guid">
                            Instagram Link
                        </label>
                        <form method="POST" action="{{ route('photograph.update.social-links', $photo->id) }}"
                              class="flex flex-row flex-no-wrap items-center justify-center">
                            @csrf
                            <input type="url" name="instagram_url" placeholder="https://"
                                   value="{{ old('instagram_url', $photo->instagram_url) }}"
                                   class="flex-grow mr-2 appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                                Save
                            </button>
                        </form>
                    </div>
                    @error('instagram_url')
                    <p class="text-red-700 text-sm italic">{{ $message }}</p>
                    @enderror

                    <!-- Fine Art America -->
                    @if(strlen(Auth::user()->fineartamerica_url) > 0)
                        <span class="block w-11/12 h-px my-10 mx-auto bg-gray-700"></span>

                        <div class="block">
                            <h3 class="text-lg mb-3">Fine Art America</h3>

                            <a href="{{ Auth::user()->fineartamerica_url }}" target="_blank"
                               class="inline-flex flex-no-wrap flex-row items-center text-blue-800 hover:text-blue-900 underline text-sm outline-none focus:shadow-outline">
                                <svg class="w-6 h-6 mr-1 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" enable-background="new 0 0 50 50"><path d="M38.288 10.297l1.414 1.415-14.99 14.99-1.414-1.414z"/><path d="M40 20h-2v-8h-8v-2h10z"/><path d="M35 38H15c-1.7 0-3-1.3-3-3V15c0-1.7 1.3-3 3-3h11v2H15c-.6 0-1 .4-1 1v20c0 .6.4 1 1 1h20c.6 0 1-.4 1-1V24h2v11c0 1.7-1.3 3-3 3z"/></svg>
                                View Profile Page (Upload Photo)
                            </a>
                        </div>
                        <label class="block text-gray-900 text-sm font-bold mt-2" for="guid">
                            Link to FineArtAmerica Prints
                        </label>
                        <form method="POST" action="{{ route('photograph.update.social-links', $photo->id) }}"
                              class="flex flex-row flex-no-wrap items-center justify-center">
                            @csrf
                            <input type="url" name="fineartamerica_url" placeholder="https://"
                                   value="{{ old('fineartamerica_url', $photo->fineartamerica_url) }}"
                                   class="flex-grow mr-2 appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                                Save
                            </button>
                        </form>
                    @endif
                    @error('fineartamerica_url')
                    <p class="text-red-700 text-sm italic">{{ $message }}</p>
                    @enderror

                    <span class="block w-11/12 h-px my-10 mx-auto bg-gray-700"></span>

                    <!-- Redbubble -->
                    <div class="block">
                        <h3 class="text-lg mb-3">Redbubble</h3>

                        <a href="https://www.redbubble.com/portfolio/images/new" target="_blank"
                           class="inline-flex flex-no-wrap flex-row items-center text-blue-800 hover:text-blue-900 underline text-sm outline-none focus:shadow-outline">
                            <svg class="w-6 h-6 mr-1 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" enable-background="new 0 0 50 50"><path d="M38.288 10.297l1.414 1.415-14.99 14.99-1.414-1.414z"/><path d="M40 20h-2v-8h-8v-2h10z"/><path d="M35 38H15c-1.7 0-3-1.3-3-3V15c0-1.7 1.3-3 3-3h11v2H15c-.6 0-1 .4-1 1v20c0 .6.4 1 1 1h20c.6 0 1-.4 1-1V24h2v11c0 1.7-1.3 3-3 3z"/></svg>
                            Upload Photo
                        </a>
                    </div>
                    <label class="block text-gray-900 text-sm font-bold mt-2" for="guid">
                        Link to Redbubble Swag
                    </label>
                    <form method="POST" action="{{ route('photograph.update.social-links', $photo->id) }}"
                          class="flex flex-row flex-no-wrap items-center justify-center">
                        @csrf
                        <input type="url" name="redbubble_url" placeholder="https://"
                               value="{{ old('redbubble_url', $photo->redbubble_url) }}"
                               class="flex-grow mr-2 appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                            Save
                        </button>
                    </form>
                    @error('redbubble_url')
                    <p class="text-red-700 text-sm italic">{{ $message }}</p>
                    @enderror

                    <!-- Etsy -->
                    <span class="block w-11/12 h-px my-10 mx-auto bg-gray-700"></span>
                    <div class="block">
                        <h3 class="text-lg mb-3">Etsy</h3>

                        <a href="{{ strlen($photo->etsy_url) > 0 ? $photo->etsy_url : 'https://www.etsy.com' }}" target="_blank"
                           class="inline-flex flex-no-wrap flex-row items-center text-blue-800 hover:text-blue-900 underline text-sm outline-none focus:shadow-outline">
                            <svg class="w-6 h-6 mr-1 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" enable-background="new 0 0 50 50"><path d="M38.288 10.297l1.414 1.415-14.99 14.99-1.414-1.414z"/><path d="M40 20h-2v-8h-8v-2h10z"/><path d="M35 38H15c-1.7 0-3-1.3-3-3V15c0-1.7 1.3-3 3-3h11v2H15c-.6 0-1 .4-1 1v20c0 .6.4 1 1 1h20c.6 0 1-.4 1-1V24h2v11c0 1.7-1.3 3-3 3z"/></svg>
                            View Etsy Page
                        </a>
                    </div>
                    <label class="block text-gray-900 text-sm font-bold mt-2" for="guid">
                        Link to Etsy Page
                    </label>
                    <form method="POST" action="{{ route('photograph.update.social-links', $photo->id) }}"
                          class="flex flex-row flex-no-wrap items-center justify-center">
                        @csrf
                        <input type="url" name="etsy_url" placeholder="https://"
                               value="{{ old('etsy_url', $photo->etsy_url) }}"
                               class="flex-grow mr-2 appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                            Save
                        </button>
                    </form>
                    @error('etsy_url')
                    <p class="text-red-700 text-sm italic">{{ $message }}</p>
                    @enderror

                    <!-- eBay -->
                    <span class="block w-11/12 h-px my-10 mx-auto bg-gray-700"></span>
                    <div class="block">
                        <h3 class="text-lg mb-3">eBay</h3>

                        <a href="{{ strlen($photo->ebay_url) > 0 ? $photo->ebay_url : 'https://www.ebay.com' }}" target="_blank"
                           class="inline-flex flex-no-wrap flex-row items-center text-blue-800 hover:text-blue-900 underline text-sm outline-none focus:shadow-outline">
                            <svg class="w-6 h-6 mr-1 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" enable-background="new 0 0 50 50"><path d="M38.288 10.297l1.414 1.415-14.99 14.99-1.414-1.414z"/><path d="M40 20h-2v-8h-8v-2h10z"/><path d="M35 38H15c-1.7 0-3-1.3-3-3V15c0-1.7 1.3-3 3-3h11v2H15c-.6 0-1 .4-1 1v20c0 .6.4 1 1 1h20c.6 0 1-.4 1-1V24h2v11c0 1.7-1.3 3-3 3z"/></svg>
                            View eBay Page
                        </a>
                    </div>
                    <label class="block text-gray-900 text-sm font-bold mt-2" for="guid">
                        Link to eBay Page
                    </label>
                    <form method="POST" action="{{ route('photograph.update.social-links', $photo->id) }}"
                          class="flex flex-row flex-no-wrap items-center justify-center">
                        @csrf
                        <input type="url" name="ebay_url" placeholder="https://"
                               value="{{ old('ebay_url', $photo->ebay_url) }}"
                               class="flex-grow mr-2 appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                            Save
                        </button>
                    </form>
                    @error('ebay_url')
                    <p class="text-red-700 text-sm italic">{{ $message }}</p>
                    @enderror

                @endif
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
