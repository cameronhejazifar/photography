<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <style>
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            min-width: 600px;
            min-height: 400px;
        }
        .background {
            background-color: black;
            background-image: url('img/background.png');
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
        }
    </style>
</head>
<body class="font-lato">

    <!-- Background / Logo -->
    <div class="background fixed inset-0 w-full h-full flex flex-col items-center justify-center z-behind pointer-events-none select-none">
        <div class="font-signerica text-logo text-white font-thin">Hejazi</div>
        <div class="mt-20 font-lato text-xl text-white tracking-logo">PHOTOGRAPHY</div>
    </div>

</body>
</html>
