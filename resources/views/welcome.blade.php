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
<div class="w-full h-24 flex flex-row items-center justify-center bg-black bg-opacity-10 backdrop-blur-10">
    <a href="{{ route('home') }}" class="mx-4 uppercase text-white text-opacity-75 hover:text-opacity-100">Home</a>
    <a href="{{ route('browse') }}" class="mx-4 uppercase text-white text-opacity-75 hover:text-opacity-100">Browse</a>
    <a href="{{ route('about') }}" class="mx-4 uppercase text-white text-opacity-75 hover:text-opacity-100">About</a>
</div>

<!-- Logo -->
<div class="p-20 flex flex-col items-center justify-center pointer-events-none select-none">
    <div class="font-signerica text-logo text-white font-thin">{{ config('app.name', 'Laravel') }}</div>
    <div class="mt-20 font-lato text-xl text-white uppercase tracking-logo">Photography</div>
</div>

<!-- Footer -->
<div class="w-full h-12 backdrop-blur-10 flex flex-row items-center justify-center">

    @if(config('app.social.flickr.enabled'))
        <a target="_blank" href="{{ config('app.social.flickr.url') }}"
           class="flickr-icon block w-6 h-6 mx-4" title="Flickr"></a>
    @endif

    @if(config('app.social.instagram.enabled'))
        <a target="_blank" href="{{ config('app.social.instagram.url') }}"
           class="instagram-icon block w-6 h-6 mx-4" title="Instagram"></a>
    @endif

    @if(config('app.social.web.enabled'))
        <a target="_blank" href="{{ config('app.social.web.url') }}"
           class="website-icon block w-6 h-6 mx-4" title="Website"></a>
    @endif

    @if(config('app.social.redbubble.enabled'))
        <a target="_blank" href="{{ config('app.social.redbubble.url') }}"
           class="redbubble-icon block w-6 h-6 mx-4" title="Redbubble"></a>
    @endif

    @if(config('app.social.fineartamerica.enabled'))
        <a target="_blank" href="{{ config('app.social.fineartamerica.url') }}"
           class="fineartamerica-icon block w-6 h-6 mx-4" title="Fine Art America"></a>
    @endif

</div>

</body>
</html>
