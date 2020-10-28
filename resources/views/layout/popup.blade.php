<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }} Photography</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body class="font-lato min-w-full min-h-full relative flex flex-col items-center justify-start bg-white">

<div class="w-full py-3 bg-black bg-opacity-75 backdrop-blur-10 relative flex flex-col items-center justify-center shadow z-10">
    <div class="flex flex-row md:flex-col items-center justify-center pointer-events-none select-none">
        <div class="font-signerica text-md md:text-xl text-white text-center font-bold">{{ config('app.name', 'Laravel') }}</div>
        <div class="mt-0 md:mt-2 ml-3 md:ml-0 font-lato text-xxs text-white text-center uppercase tracking-widest">Photography</div>
    </div>
</div>

<div class="w-full max-w-screen-xl flex flex-col items-center justify-center">
    @yield('content')
</div>

<script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
</body>
</html>
