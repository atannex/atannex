<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    @include('components.layouts.header')

    <link rel="stylesheet" href="{{ asset('video/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('video/css/splide.min.css') }}">
    <link rel="stylesheet" href="{{ asset('video/css/slimselect.css') }}">
    <link rel="stylesheet" href="{{ asset('video/css/plyr.css') }}">
    <link rel="stylesheet" href="{{ asset('video/css/photoswipe.css') }}">
    <link rel="stylesheet" href="{{ asset('video/css/default-skin.css') }}">
    <link rel="stylesheet" href="{{ asset('video/css/main.css') }}">

    <link rel="stylesheet" href="{{ asset('video/webfont/tabler-icons.min.css') }}">

    @livewireStyles

    <x-layouts.googletagmanager />

</head>

<body>

    @yield('base-video')

    @livewireScripts

    <script src="{{ asset('video/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('video/js/splide.min.js') }}"></script>
    <script src="{{ asset('video/js/slimselect.min.js') }}"></script>
    <script src="{{ asset('video/js/smooth-scrollbar.js') }}"></script>
    <script src="{{ asset('video/js/plyr.min.js') }}"></script>
    <script src="{{ asset('video/js/photoswipe.min.js') }}"></script>
    <script src="{{ asset('video/js/photoswipe-ui-default.min.js') }}"></script>
    <script src="{{ asset('video/js/main.js') }}"></script>
</body>
</html>
