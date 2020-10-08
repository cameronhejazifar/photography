@extends('layout.main', ['navLogo' => true])

@section('content')

    <form method="POST" action="{{ route('login') }}"
          class="bg-white bg-opacity-75 backdrop-blur-10 rounded-lg shadow-md w-full max-w-lg my-10 p-10">
        @csrf

        <h1 class="text-2xl mb-10">Login</h1>

        <!-- Email -->
        <div class="mb-4">
            <label class="block text-gray-900 text-sm font-bold mb-2" for="email">
                Email
            </label>
            <input type="email" name="email" placeholder="Email" autofocus
                   class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
            @error('email')
            <p class="text-red-700 text-sm italic">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-6">
            <label class="block text-gray-900 text-sm font-bold mb-2" for="password">
                Password
            </label>
            <input type="password" name="password" placeholder="Password"
                   class="appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline bg-white transition-all duration-200 ease-in-out">
            @error('password')
            <p class="text-red-700 text-sm italic">{{ $message }}</p>
            @enderror
        </div>

        <!-- Sign In Button -->
        <button type="submit"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
            Sign In
        </button>

    </form>

@endsection
