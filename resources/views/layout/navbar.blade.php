@if($navLogo)
    <div class="w-full h-24 md:h-32 bg-transparent relative pointer-events-none select-none z-behind"></div>
@else
    <div class="w-full h-16 md:h-24 bg-transparent relative pointer-events-none select-none z-behind"></div>
@endif
<div class="w-full bg-black bg-opacity-25 backdrop-blur-10 fixed flex flex-col items-center justify-center shadow z-10">

    @if($navLogo)
        <a href="{{ route('home') }}" style="margin-bottom: -24px" class="h-12 z-10">
            <div class="pt-2 pb-2 flex flex-row md:flex-col items-center justify-center pointer-events-none select-none">
                <div class="font-signerica text-md md:text-xl text-white text-center font-bold">{{ config('app.name', 'Laravel') }}</div>
                <div class="mt-0 md:mt-2 ml-3 md:ml-0 font-lato text-xxs text-white text-center uppercase tracking-widest">Photography</div>
            </div>
        </a>
    @endif

    <div class="h-16 md:h-24 w-full max-w-screen-xl {{ $navLogo ? 'mt-2 md:mt-4 pt-2 md:pt-4' : '' }} flex flex-row items-center justify-center">
        <a href="{{ route('home') }}"
           class="mx-4 text-md md:text-lg uppercase text-white text-opacity-75 hover:text-opacity-100 transition-all duration-200 ease-in-out">
            Home
        </a>
        <a href="{{ route('browse') }}"
           class="mx-4 text-md md:text-lg uppercase text-white text-opacity-75 hover:text-opacity-100 transition-all duration-200 ease-in-out">
            Browse
        </a>
        <a href="{{ route('about') }}"
           class="mx-4 text-md md:text-lg uppercase text-white text-opacity-75 hover:text-opacity-100 transition-all duration-200 ease-in-out">
            About
        </a>
        @auth
            <a href="{{ route('profile') }}"
               class="mx-4 text-md md:text-lg uppercase text-white text-opacity-75 hover:text-opacity-100 transition-all duration-200 ease-in-out">
                Profile
            </a>
            <a href="{{ route('logout') }}"
               class="mx-4 text-md md:text-lg uppercase text-white text-opacity-75 hover:text-opacity-100 transition-all duration-200 ease-in-out">
                Logout
            </a>
        @endif
    </div>
</div>
