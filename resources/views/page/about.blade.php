<?php
$users = App\Models\User::all();
?>

@extends('layout.main', ['navLogo' => true])

@section('content')

    <div class="bg-white bg-opacity-75 backdrop-blur-10 rounded-lg shadow-md w-full my-10 p-10 md:p-20">

        @foreach($users as $index => $user)

            <?php
                $even = ($index % 2) === 0;
                $odd = ($index % 2) === 1;
            ?>

            @if($index > 0)
                <span class="block w-full h-px my-10 md:my-20 mx-auto bg-gray-700"></span>
            @endif

            <div class="flex {{ $even ? 'flex-row' : 'flex-row-reverse' }} flex-wrap md:flex-no-wrap items-center justify-center">

                <!-- Profile Picture -->
                <img class="h-auto w-full md:w-1/3 {{ $even ? 'mr-0 md:mr-20' : 'ml-0 md:ml-20' }} mb-10 md:mb-0 object-contain"
                     alt="{{ $user->alias }}" title="{{ $user->alias }}"
                     src="{{ $user->profilePictureURL() }}"/>

                <div class="flex-grow w-full md:w-auto">
                    <h1 class="{{ $even ? 'text-left' : 'text-right' }} text-2xl text-gray-800 font-bold mb-10">
                        {{ $user->name }}
                    </h1>

                    <div class="flex {{ $even ? 'flex-row' : 'flex-row-reverse' }} flex-no-wrap items-stretch justify-start">

                        <div class="flex flex-col flex-no-wrap items-center justify-center">
                            <div class="block w-px h-4 bg-gray-700"></div>
                            <span class="my-2 text-md text-vertical uppercase tracking-about text-gray-800">
                                About
                            </span>
                            <div class="block w-px flex-grow bg-gray-700"></div>
                        </div>

                        <p class="{{ $even ? 'ml-10 text-left' : 'mr-10 text-right' }} flex-grow">
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                            {{ $user->biography }}
                        </p>
                    </div>
                </div>

            </div>
        @endforeach
    </div>

@endsection
