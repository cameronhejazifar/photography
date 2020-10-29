@extends('layout.main', ['navLogo' => true])

@section('content')

    <div class="bg-white bg-opacity-75 backdrop-blur-10 rounded-lg shadow-md w-full my-10 p-10">

        <!-- Header -->
        <div class="flex flex-row flex-no-wrap items-center justify-between mb-10">

            <h1 class="text-2xl">My Profile</h1>

            <button type="button"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                View/Edit Photos
            </button>

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
                <form method="POST" action="{{ route('profile') }}">
                    @csrf

                    <!-- Account Active -->
                    <div class="mb-4">
                        <label class="block text-gray-900 text-sm font-bold mb-2" for="active">
                            <input class="mr-2 leading-tight" type="checkbox" name="active"
                                {{ Auth::user()->active ? 'checked' : '' }} value="1">
                            <span>Account Active</span>
                        </label>
                        @error('active')
                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-gray-900 text-sm font-bold mb-2" for="email">
                            Email
                        </label>
                        <input type="email" name="email" placeholder="Email" value="{{ old('email', Auth::user()->email) }}"
                               readonly
                               class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 cursor-not-allowed opacity-50 leading-tight focus:outline-none focus:shadow-outline bg-white duration-200 ease-in-out">
                        @error('email')
                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div class="mb-4">
                        <label class="block text-gray-900 text-sm font-bold mb-2" for="name">
                            Full Name
                        </label>
                        <input type="text" name="name" placeholder="Name" value="{{ old('name', Auth::user()->name) }}"
                               class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                        @error('name')
                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alias -->
                    <div class="mb-4">
                        <label class="block text-gray-900 text-sm font-bold mb-2" for="alias">
                            Alias (Display Name)
                        </label>
                        <input type="text" name="alias" placeholder="Alias" value="{{ old('alias', Auth::user()->alias) }}"
                               class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                        @error('alias')
                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date of Birth -->
                    <div class="mb-4">
                        <label class="block text-gray-900 text-sm font-bold mb-2" for="date_of_birth">
                            Date of Birth
                        </label>
                        <input type="date" name="date_of_birth" placeholder="YYYY-MM-DD"
                               min="{{ \Carbon\Carbon::now()->subYears(120)->format('Y-m-d') }}"
                               max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                               value="{{ old('date_of_birth', Auth::user()->date_of_birth->format('Y-m-d')) }}"
                               class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                        @error('date_of_birth')
                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Biography -->
                    <div class="mb-4">
                        <label class="block text-gray-900 text-sm font-bold mb-2" for="biography">
                            Biography
                        </label>
                        <textarea name="biography" placeholder="Biography"
                                  class="appearance-none border border-gray-600 rounded w-full h-24 py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">{{Auth::user()->biography }}</textarea>
                        @error('biography')
                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Photograph Checklist -->
                    <div class="mb-4">
                        <label class="block text-gray-900 text-sm font-bold mb-2" for="photograph_checklist">
                            Photograph Checklist
                        </label>
                        <textarea name="photograph_checklist" placeholder="Separate list items with a new line"
                                  class="appearance-none border border-gray-600 rounded w-full h-24 py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">{{Auth::user()->photograph_checklist }}</textarea>
                        @error('photograph_checklist')
                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Update Button -->
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                        Update Profile
                    </button>
                </form>

                <span class="block w-11/12 h-px mt-10 mx-auto bg-gray-700"></span>

                <!-- Update Password Form -->
                <form method="POST" action="{{ route('profile.password') }}" class="mt-10">
                    @csrf

                    <!-- Password -->
                    <div class="mb-6">
                        <label class="block text-gray-900 text-sm font-bold mb-2" for="old_password">
                            Update Password
                        </label>
                        <input type="password" name="old_password" placeholder="Old Password"
                               class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                        @error('old_password')
                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                        @enderror
                        <input type="password" name="new_password" placeholder="New Password"
                               class="appearance-none border border-gray-600 rounded mt-2 w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                        @error('new_password')
                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                        @enderror
                        <input type="password" name="new_password_confirmation"
                               placeholder="Confirm New Password"
                               class="appearance-none border border-gray-600 rounded mt-2 w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                        @error('new_password_confirmation')
                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Change Password Button -->
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                        Update Password
                    </button>
                </form>

            </div>

            <span class="block md:hidden w-11/12 h-px mt-10 mx-auto bg-gray-700"></span>

            <!-- Right Column -->
            <div class="w-full md:w-1/2 md:pl-10 mt-10 md:mt-0">

                <!-- Profile Icon Form -->
                <form method="POST" action="{{ route('profile.upload-icon') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Title -->
                    <label class="block text-gray-900 text-sm font-bold mb-2" for="image">
                        Profile Icon (Small)
                    </label>

                    <!-- Icon -->
                    <img class="w-12 h-12 mb-4 rounded-full object-cover"
                         alt="{{ Auth::user()->alias }}" title="{{ Auth::user()->alias }}"
                         src="{{ Auth::user()->profileIconURL() }}?refresh={{ Str::random(10) }}"/>

                    <!-- Icon + Image Selector -->
                    <div class="flex flex-row flex-no-wrap items-center justify-start">
                        <input type="file" name="image" placeholder="Select a file">
                    </div>
                    @error('image')
                    <p class="text-red-700 text-sm italic">{{ $message }}</p>
                    @enderror

                    <!-- Upload Button -->
                    <button type="submit"
                            class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                        Update Profile Icon
                    </button>
                </form>

                <span class="block w-11/12 h-px mt-10 mx-auto bg-gray-700"></span>

                <!-- Profile Picture Form -->
                <form method="POST" action="{{ route('profile.upload-picture') }}" enctype="multipart/form-data"
                      class="mt-10">
                    @csrf

                    <!-- Title -->
                    <label class="block text-gray-900 text-sm font-bold mb-2" for="image">
                        Profile Picture (Large)
                    </label>

                    <!-- Picture -->
                    <img class="w-auto h-64 mb-4 object-contain"
                         alt="{{ Auth::user()->alias }}" title="{{ Auth::user()->alias }}"
                         src="{{ Auth::user()->profilePictureURL() }}?refresh={{ Str::random(10) }}"/>

                    <!-- Image Selector -->
                    <div class="flex flex-row flex-no-wrap items-center justify-start">
                        <input type="file" name="image" placeholder="Select a file">
                    </div>
                    @error('image')
                    <p class="text-red-700 text-sm italic">{{ $message }}</p>
                    @enderror

                    <!-- Upload Button -->
                    <button type="submit"
                            class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                        Update Profile Picture
                    </button>
                </form>
            </div>

        </div>

        <span class="block w-11/12 h-px mt-10 mx-auto bg-gray-700"></span>

        <h1 class="text-2xl my-10">Services</h1>

        <!-- Bottom Section -->
        <div class="flex flex-row flex-wrap justify-start items-start">

            <!-- Left Column -->
            <div class="w-full md:w-1/2 md:pr-10">

                <!-- Google Drive -->
                <form method="POST" action="{{ route('profile.update-googledrive') }}">
                    @csrf

                    <h3 class="text-lg mb-3">Google Drive</h3>

                    <!-- Edited Photos Directory -->
                    <div class="mb-4">
                        <label class="block text-gray-900 text-sm font-bold mb-2" for="gd-edited-photos">
                            Edited Photos Directory
                        </label>
                        <input type="text" name="google_drive_dir_edits" id="gd-edited-photos"
                               placeholder="Path for JPEG files, ie: /Photography/Edits"
                               value="{{ old('google_drive_dir_edits', Auth::user()->google_drive_dir_edits) }}"
                               class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                        @error('google_drive_dir_edits')
                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Raw Files Directory -->
                    <div class="mb-4">
                        <label class="block text-gray-900 text-sm font-bold mb-2" for="gd-raw">
                            Raw Files Directory
                        </label>
                        <input type="text" name="google_drive_dir_raws" id="gd-raw"
                               placeholder="Path for RAW files, ie: /Photography/Raw"
                               value="{{ old('google_drive_dir_raws', Auth::user()->google_drive_dir_raws) }}"
                               class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                        @error('google_drive_dir_raws')
                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Metadata Directory -->
                    <div class="mb-4">
                        <label class="block text-gray-900 text-sm font-bold mb-2" for="gd-meta">
                            Metadata Directory
                        </label>
                        <input type="text" name="google_drive_dir_metas" id="gd-meta"
                               placeholder="Path for XMP files, ie: /Photography/Lightroom"
                               value="{{ old('google_drive_dir_metas', Auth::user()->google_drive_dir_metas) }}"
                               class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                        @error('google_drive_dir_metas')
                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Save Google Drive Button -->
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                        Save
                    </button>

                </form>

            </div>

            <!-- Right Column -->
            <div class="w-full md:w-1/2 md:pl-10 mt-10 md:mt-0 md:border-l border-gray-700">

                <span class="block md:hidden w-11/12 h-px my-10 mx-auto bg-gray-700"></span>

                <!-- Nixplay -->
                <form method="POST" action="{{ route('profile.update-nixplay') }}">
                    @csrf

                    <h3 class="text-lg mb-3">Nixplay</h3>

                    <!-- Link to Nixplay Album -->
                    <div class="mb-4">
                        <label class="block text-gray-900 text-sm font-bold mb-2" for="nixplay-url">
                            Link to Nixplay
                        </label>
                        <input type="url" name="nixplay_url" id="nixplay-url"
                               placeholder="https://"
                               value="{{ old('nixplay_url', Auth::user()->nixplay_url) }}"
                               class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                        @error('nixplay_url')
                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Save Nixplay Button -->
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                        Save
                    </button>

                </form>

                <span class="block w-11/12 h-px my-10 mx-auto bg-gray-700"></span>

                <!-- Fine Art America -->
                <form method="POST" action="{{ route('profile.update-fineartamerica') }}">
                    @csrf

                    <h3 class="text-lg mb-3">Fine Art America</h3>

                    <!-- Link to Fine Art America Album -->
                    <div class="mb-4">
                        <label class="block text-gray-900 text-sm font-bold mb-2" for="fineartamerica-url">
                            Link to Fine Art America
                        </label>
                        <input type="url" name="fineartamerica_url" id="fineartamerica-url"
                               placeholder="https://"
                               value="{{ old('fineartamerica_url', Auth::user()->fineartamerica_url) }}"
                               class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                        @error('fineartamerica_url')
                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Save Fine Art America Button -->
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                        Save
                    </button>

                </form>

            </div>

        </div>

    </div>

@endsection
