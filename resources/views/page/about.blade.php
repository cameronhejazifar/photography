@extends('layout.main')

@section('header')
    <a href="{{ route('home') }}" style="margin-bottom: -24px" class="z-10">
        @include('layout.logo-small')
    </a>
@endsection

@section('content')

    <div class="bg-white bg-opacity-75 backdrop-blur-10 rounded-lg mt-20 mb-10 px-20 py-64 w-full md:w-11/12 lg:w-10/12 xl:w-9/12">
        Placeholder text
    </div>

@endsection
