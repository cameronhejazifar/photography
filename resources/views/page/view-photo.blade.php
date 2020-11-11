<?php
$edit = $photo->photographEdits('large')->firstOrFail();
?>

@extends('layout.main', ['navLogo' => true])

@section('content')

    <!-- Back Button -->
    <div class="w-full mt-10 mb-2 px-5 lg:px-10">
        <a href="{{ url()->previous() }}" class="text-white uppercase text-xs font-bold opacity-75 hover:opacity-100">
            &larr; Back to Photos
        </a>
    </div>

    <div class="w-full flex flex-row flex-wrap lg:flex-no-wrap items-center justify-center">

        <!-- Left Column -->
        <div class="w-full lg:w-2/3 p-5 lg:p-10">
            <div id="picture-container" class="relative block w-full">

                <!-- Spinner -->
                <span id="picture-spinner"
                      class="absolute top-0 left-0 w-full h-full z-10 flex items-center justify-center pointer-events-none transition-opacity duration-500 ease-in-out opacity-100">
                    <svg class="block w-10 h-10 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>

                <!-- Picture -->
                <span id="picture"
                      data-image-url="{{ $edit->imageURL() }}"
                      data-original-width="{{ $edit->original_width }}" data-scaled-width="{{ $edit->scaled_width }}"
                      data-original-height="{{ $edit->original_height }}" data-scaled-height="{{ $edit->scaled_height }}"
                      class="absolute block left-0 top-0 w-full h-full bg-transparent bg-center bg-no-repeat bg-contain shadow-lg z-0 transition-opacity duration-500 ease-in-out opacity-0"></span>

            </div>
        </div>

        <!-- Right Column -->
        <div class="w-full lg:w-1/3 ml-5 lg:ml-0 mr-5 lg:mr-10">

            <!-- Title / Name -->
            <h3 class="text-4xl text-white text-left lg:text-right font-light leading-tight uppercase mt-3 mb-2 lg:mb-5">
                {{ $photo->name }}
            </h3>

            <!-- Description -->
            <div class="w-full mb-2 lg:mb-5 text-gray-300 text-left lg:text-right text-sm italic">
                by {{ $photo->user->name }}
            </div>

            <!-- Location -->
            <div class="w-full mb-2 lg:mb-5 flex flex-row items-center justify-start lg:justify-end text-gray-500 text-lg">
                <svg class="w-4 h-4 flex-shrink-0 mr-2 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 0c-4.198 0-8 3.403-8 7.602 0 4.198 3.469 9.21 8 16.398 4.531-7.188 8-12.2 8-16.398 0-4.199-3.801-7.602-8-7.602zm0 11c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3z"/></svg>
                <span>{{ $photo->location }}</span>
            </div>

            <!-- Description -->
            <div class="w-full mb-8 lg:mb-16 text-gray-300 text-left lg:text-right text-md">
                {{ $photo->description }}
            </div>

            <!-- Purchase Buttons -->
            <div class="mb-8 lg:mb-16 flex flex-row flex-wrap items-start justify-start lg:justify-end">

                <!-- Fine Art America (Prints) -->
                @if(strlen($photo->fineartamerica_url) > 0)
                    <a href="{{ $photo->fineartamerica_url }}" target="_blank"
                       class="ml-2 mt-2 bg-green-300 bg-opacity-10 hover:bg-opacity-25 text-green-300 font-normal py-2 px-4 border border-green-300 rounded-sm focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                        Buy Prints
                    </a>
                @endif

                <!-- Redbubble (Swag) -->
                @if(strlen($photo->redbubble_url) > 0)
                    <a href="{{ $photo->redbubble_url }}" target="_blank"
                       class="ml-2 mt-2 bg-purple-200 bg-opacity-10 hover:bg-opacity-25 text-purple-200 font-normal py-2 px-4 border border-purple-200 rounded-sm focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                        Buy Swag
                    </a>
                @endif

                <!-- Etsy (Items) -->
                @if(strlen($photo->etsy_url) > 0)
                    <a href="{{ $photo->etsy_url }}" target="_blank"
                       class="ml-2 mt-2 bg-orange-200 bg-opacity-10 hover:bg-opacity-25 text-orange-200 font-normal py-2 px-4 border border-orange-200 rounded-sm focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                        Buy on Etsy
                    </a>
                @endif

                <!-- eBay (Auction) -->
                @if(strlen($photo->ebay_url) > 0)
                    <a href="{{ $photo->ebay_url }}" target="_blank"
                       class="ml-2 mt-2 bg-blue-300 bg-opacity-10 hover:bg-opacity-25 text-blue-300 font-normal py-2 px-4 border border-blue-300 rounded-sm focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                        Buy on eBay
                    </a>
                @endif

            </div>

            <!-- External App Links -->
            <div class="mb-2 lg:mb-5 flex flex-row flex-wrap items-start justify-start lg:justify-end">

                <!-- Instagram Button -->
                @if(strlen($photo->instagram_url) > 0)
                    <a href="{{ $photo->instagram_url }}" target="_blank"
                       class="ml-2 mt-2 flex flex-row flex-no-wrap items-center justify-center bg-gradient-to-r from-yellow-500 via-pink-600 to-purple-600 hover:from-yellow-600 hover:via-pink-700 hover:to-purple-700 text-pink-100 font-normal py-2 px-4 rounded-sm focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                        <img class="w-5 h-5 mr-3" src="{{ asset('img/footer/instagram.png') }}" alt="Instagram"/>
                        <span>View On Instagram</span>
                    </a>
                @endif

                <!-- Flickr Button -->
                @if($photo->flickrPosts()->count() > 0)
                    <a href="{{ $photo->flickrPosts()->first()->flickrURL() }}" target="_blank"
                       class="ml-2 mt-2 flex flex-row flex-no-wrap items-center justify-center bg-gradient-to-r from-blue-500 to-pink-600 hover:from-blue-600 hover:to-pink-700 text-blue-100 font-normal py-2 px-4 rounded-sm focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out">
                        <img class="w-5 h-5 mr-3" src="{{ asset('img/footer/flickr.png') }}" alt="Flickr"/>
                        <span>View On Flickr</span>
                    </a>
                @endif

            </div>

        </div>

    </div>

@endsection
