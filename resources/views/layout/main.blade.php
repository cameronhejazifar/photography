<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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

</body>
</html>
