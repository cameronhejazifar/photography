@extends('layout.main', ['navLogo' => false])

@section('content')

    <div class="p-10 md:p-20 flex flex-col items-center justify-center pointer-events-none select-none">
        <div class="font-signerica text-logo-mobile md:text-logo text-white text-center font-thin">{{ config('app.name', 'Laravel') }}</div>
        <div class="mt-10 md:mt-20 font-lato text-sm md:text-xl text-white text-center uppercase tracking-logo-mobile md:tracking-logo">Photography</div>
    </div>

@endsection
