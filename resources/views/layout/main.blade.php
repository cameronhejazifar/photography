<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <title>{{ config('app.name', 'Laravel') }} Photography</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body class="font-lato min-w-full min-h-full relative flex flex-col items-center justify-between">

<div class="background fixed inset-0 w-full h-full z-behind pointer-events-none select-none"></div>

@include('layout.navbar', compact('navLogo'))

<div class="w-full max-w-screen-xl flex flex-col items-center justify-center">
    @yield('content')
</div>

@include('layout.footer')

<script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
</body>
</html>
