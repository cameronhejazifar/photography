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
            <div class="w-full md:w-1/2 md:pr-5">

                <!-- Profile Info Form -->
                <form method="POST" action="{{ route('profile') }}">
                    @csrf

                    <!-- Account Active -->
                    <div class="mb-4">
                        <label class="block text-gray-900 text-sm font-bold mb-2" for="active">
                            <input class="mr-2 leading-tight" type="checkbox" id="active" name="active"
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
                        <input type="email" id="email" name="email" placeholder="Email" value="{{Auth::user()->email }}"
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
                        <input type="text" id="name" name="name" placeholder="Name" value="{{Auth::user()->name }}"
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
                        <input type="text" id="alias" name="alias" placeholder="Alias" value="{{Auth::user()->alias }}"
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
                        <input type="date" id="date_of_birth" name="date_of_birth" placeholder="YYYY-MM-DD"
                               min="{{ \Carbon\Carbon::now()->subYears(120)->format('Y-m-d') }}"
                               max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                               value="{{Auth::user()->date_of_birth->format('Y-m-d') }}"
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
                        <textarea id="biography" name="biography" placeholder="Biography"
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
                        <textarea id="photograph_checklist" name="photograph_checklist"
                                  placeholder="Separate list items with a new line"
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
            </div>

            <!-- Right Column -->
            <div class="w-full md:w-1/2 md:pl-5 mt-10 md:mt-0">

                <!-- Profile Info Form -->
                <form method="POST" action="{{ route('profile.password') }}" class="mt-10">
                    @csrf

                    <!-- Password -->
                    <div class="mb-6">
                        <label class="block text-gray-900 text-sm font-bold mb-2" for="old_password">
                            Update Password
                        </label>
                        <input type="password" id="old_password" name="old_password" placeholder="Old Password"
                               class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                        @error('old_password')
                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                        @enderror
                        <input type="password" id="new_password" name="new_password" placeholder="New Password"
                               class="appearance-none border border-gray-600 rounded mt-2 w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
                        @error('new_password')
                        <p class="text-red-700 text-sm italic">{{ $message }}</p>
                        @enderror
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation"
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

        </div>

        <h1 class="text-2xl my-10">Linked Services</h1>

        <!-- Bottom Section -->
        <div class="flex flex-row flex-wrap justify-start items-start">

            <!-- Left Column -->
            <div class="w-full md:w-1/2 md:pr-5">
                <!-- TODO: add services here -->
                Placeholder for services, ie: Google Drive, Nixplay, FineArtsAmerica, etc.
            </div>
        </div>

    </div>

@endsection
