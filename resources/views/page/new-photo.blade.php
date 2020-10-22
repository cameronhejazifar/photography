<?php
$oldTags = old('tags', []);
?>

@extends('layout.main', ['navLogo' => true])

@section('content')

    <div class="bg-white bg-opacity-75 backdrop-blur-10 rounded-lg shadow-md w-full my-10 p-10">

        <h1 class="text-2xl mb-10">New Photograph</h1>

        <!-- New Photo Form -->
        <form method="POST" action="{{ route('photograph.create') }}"
              class="flex flex-row flex-wrap justify-start items-start mt-10">
            @csrf

            <!-- Left Column -->
            <div class="w-full md:w-1/2 md:pr-5">

                <!-- Identifier -->
                <div class="mb-4">
                    <label class="block text-gray-900 text-sm font-bold mb-2" for="guid">
                        Identifier
                    </label>
                    <input type="text" name="guid" placeholder="Identifier" value="{{ old('guid', Str::uuid()) }}"
                           readonly
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
                            <option value="active">
                                Active Listing
                            </option>
                            <option value="inactive" selected>
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
                    <input type="text" name="name" placeholder="Photo Name" value="{{ old('name') }}"
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
                    <input type="text" name="location" placeholder="Location" value="{{ old('location') }}"
                           class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                    @error('location')
                    <p class="text-red-700 text-sm italic">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Save Button -->
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                    Save
                </button>

            </div>

            <!-- Right Column -->
            <div class="w-full md:w-1/2 md:pl-5 mt-10 md:mt-0">

                <!-- Description -->
                <div class="mb-4">
                    <label class="block text-gray-900 text-sm font-bold mb-2" for="description">
                        Description
                    </label>
                    <textarea name="description" placeholder="Description"
                              class="appearance-none border border-gray-600 rounded w-full h-24 py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="text-red-700 text-sm italic">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tags -->
                <div class="mb-4">
                    <label class="block text-gray-900 text-sm font-bold mb-1" for="tags-input">Tags (<span id="tags-count">0</span>)</label>

                    <div id="tag-input-container"></div>
                    <div id="tag-display-container" class="flex flex-wrap flex-row items-start justify-start"></div>

                    <input id="tags-input" type="text" placeholder="Tags&#8230; (press enter to add)"
                           class="appearance-none border border-gray-600 rounded w-full mt-1 py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                    @error('tags')
                    <p class="text-red-700 text-sm italic">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </form>
    </div>

    <script type="text/javascript">
        window.addEventListener("load", function () {
            @foreach($oldTags as $oldTag)
            window.addNewPhotoTag('{!! $oldTag !!}');
            @endforeach
        });
    </script>

@endsection
