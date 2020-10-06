<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }} Photography</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <style>
        html {
            width: 100%;
            height: 100%;
            min-width: 600px;
            min-height: 400px;
        }

        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .background {
            background-color: black;
            background-image: url('img/background.png');
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
        }

        .instagram-icon {
            background: transparent url('/img/footer/instagram.png') no-repeat center center;
            background-size: contain;
        }

        .flickr-icon {
            background: transparent url('/img/footer/flickr.png') no-repeat center center;
            background-size: contain;
        }

        .website-icon {
            background: transparent url('/img/footer/website.png') no-repeat center center;
            background-size: contain;
        }

        .redbubble-icon {
            background: transparent url('/img/footer/redbubble.png') no-repeat center center;
            background-size: contain;
        }

        .fineartamerica-icon {
            background: transparent url('/img/footer/fineartamerica.png') no-repeat center center;
            background-size: contain;
        }
    </style>
</head>
<body class="font-lato min-w-full min-h-full relative flex flex-col items-center justify-between">

<!-- Background -->
<div class="background fixed inset-0 w-full h-full z-behind pointer-events-none select-none"></div>

<!-- Nav Bar -->
<div class="w-full h-24 bg-transparent relative pointer-events-none select-none z-behind"></div>
<div class="w-full bg-black bg-opacity-25 backdrop-blur-10 fixed flex flex-col items-center justify-center z-10">
    @yield('header')
    <div class="h-24 w-full max-w-screen-xl flex flex-row items-center justify-center">
        <a href="{{ route('home') }}"
           class="mx-4 text-lg uppercase text-white text-opacity-75 hover:text-opacity-100 transition-all duration-200 ease-in-out">
            Home
        </a>
        <a href="{{ route('browse') }}"
           class="mx-4 text-lg uppercase text-white text-opacity-75 hover:text-opacity-100 transition-all duration-200 ease-in-out">
            Browse
        </a>
        <a href="{{ route('about') }}"
           class="mx-4 text-lg uppercase text-white text-opacity-75 hover:text-opacity-100 transition-all duration-200 ease-in-out">
            About
        </a>
    </div>
</div>

<div class="w-full max-w-screen-xl flex flex-col items-center justify-center">
    @yield('content')
</div>

<!-- Footer -->
<div class="w-full h-12 backdrop-blur-10 flex flex-col items-center justify-center z-10">
    <div class="h-full w-full max-w-screen-xl flex flex-row items-center justify-center">

        @if(config('app.social.flickr.enabled'))
            <a target="_blank" href="{{ config('app.social.flickr.url') }}" title="Flickr"
               class="flex justify-center items-center h-full px-4 opacity-75 hover:opacity-100 transition-all duration-200 ease-in-out">
                <span class="flickr-icon block w-6 h-6"></span>
            </a>
        @endif

        @if(config('app.social.instagram.enabled'))
            <a target="_blank" href="{{ config('app.social.instagram.url') }}" title="Instagram"
               class="flex justify-center items-center h-full px-4 opacity-75 hover:opacity-100 transition-all duration-200 ease-in-out">
                <span class="instagram-icon block w-6 h-6"></span>
            </a>
        @endif

        @if(config('app.social.web.enabled'))
            <a target="_blank" href="{{ config('app.social.web.url') }}" title="Website"
               class="flex justify-center items-center h-full px-4 opacity-75 hover:opacity-100 transition-all duration-200 ease-in-out">
                <span class="website-icon block w-6 h-6"></span>
            </a>
        @endif

        @if(config('app.social.redbubble.enabled'))
            <a target="_blank" href="{{ config('app.social.redbubble.url') }}" title="Redbubble"
               class="flex justify-center items-center h-full px-4 opacity-75 hover:opacity-100 transition-all duration-200 ease-in-out">
                <span class="redbubble-icon block w-6 h-6"></span>
            </a>
        @endif

        @if(config('app.social.fineartamerica.enabled'))
            <a target="_blank" href="{{ config('app.social.fineartamerica.url') }}" title="Fine Art America"
               class="flex justify-center items-center h-full px-4 opacity-75 hover:opacity-100 transition-all duration-200 ease-in-out">
                <span class="fineartamerica-icon block w-6 h-6"></span>
            </a>
        @endif

    </div>
</div>

</body>
</html>
