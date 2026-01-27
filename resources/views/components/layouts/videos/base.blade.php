<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php($assetVersion = '1.0.0')

    @include('components.layouts.header')

    <style>
        .video-thumbnail {
            width: 100%;
            max-width: 270px;
            aspect-ratio: 2 / 3;
            object-fit: cover;
        }

    </style>

    <link rel="stylesheet" href="{{ asset('video/css/bootstrap.min.css') }}?v={{ $assetVersion }}">
    <link rel="stylesheet" href="{{ asset('video/css/splide.min.css') }}?v={{ $assetVersion }}">
    <link rel="stylesheet" href="{{ asset('video/css/slimselect.css') }}?v={{ $assetVersion }}">
    <link rel="stylesheet" href="{{ asset('video/css/plyr.css') }}?v={{ $assetVersion }}">
    <link rel="stylesheet" href="{{ asset('video/css/photoswipe.css') }}?v={{ $assetVersion }}">
    <link rel="stylesheet" href="{{ asset('video/css/default-skin.css') }}?v={{ $assetVersion }}">
    <link rel="stylesheet" href="{{ asset('video/css/main.css') }}?v={{ $assetVersion }}">

    <link rel="stylesheet" href="{{ asset('video/webfont/tabler-icons.min.css') }}?v={{ $assetVersion }}">

    @livewireStyles
    @stack('styles')

    <x-layouts.googletagmanager />
</head>

<body>

    @yield('base-video')

    @livewireScripts

    <script src="{{ asset('video/js/bootstrap.bundle.min.js') }}?v={{ $assetVersion }}"></script>
    <script src="{{ asset('video/js/splide.min.js') }}?v={{ $assetVersion }}"></script>
    <script src="{{ asset('video/js/slimselect.min.js') }}?v={{ $assetVersion }}"></script>
    <script src="{{ asset('video/js/smooth-scrollbar.js') }}?v={{ $assetVersion }}"></script>
    <script src="{{ asset('video/js/plyr.min.js') }}?v={{ $assetVersion }}"></script>
    <script src="{{ asset('video/js/photoswipe.min.js') }}?v={{ $assetVersion }}"></script>
    <script src="{{ asset('video/js/photoswipe-ui-default.min.js') }}?v={{ $assetVersion }}"></script>
    <script src="{{ asset('video/js/main.js') }}?v={{ $assetVersion }}"></script>

    @stack('scripts')
</body>
</html>
