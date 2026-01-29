<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php($assetVersion = '1.0.0')

    @include('components.layouts.header')

    <style>
        /* Rating Container */
        .sign__group {
            margin-bottom: 1.5rem;
        }

        .sign__label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
            font-size: 0.95rem;
        }

        /* Stars Container */
        .sign__stars {
            display: flex;
            gap: 0.25rem;
            align-items: center;
            flex-wrap: wrap;
        }

        /* Star Button */
        .star-button {
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 0.25rem;
            font-size: 1.5rem;
            line-height: 1;
            transition: transform 0.2s ease, opacity 0.2s ease;
            position: relative;
        }

        .star-button:hover {
            transform: scale(1.15);
        }

        .star-button:active {
            transform: scale(0.95);
        }

        .star-button:focus-visible {
            outline: 2px solid #4A90E2;
            outline-offset: 2px;
            border-radius: 4px;
        }

        /* Star Icons */
        .star-button.filled i {
            color: #FFD700;
            filter: drop-shadow(0 1px 2px rgba(255, 215, 0, 0.3));
        }

        .star-button.empty i {
            color: #D1D5DB;
        }

        .star-button.empty:hover i {
            color: #FFD700;
            opacity: 0.6;
        }

        /* Hover Effect - Preview rating */
        .sign__stars:hover .star-button.empty i {
            color: #E5E7EB;
        }

        .sign__stars .star-button:hover~.star-button.empty i {
            color: #D1D5DB;
        }

        .sign__stars .star-button:hover i {
            color: #FFD700;
        }

        /* Rating Text */
        .sign__rating-text {
            display: inline-block;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: #666;
            font-weight: 500;
        }

        /* Error Message */
        .sign__error {
            display: block;
            margin-top: 0.5rem;
            color: #DC2626;
            font-size: 0.875rem;
        }

        /* Responsive Design */
        @media (max-width: 640px) {
            .star-button {
                font-size: 1.35rem;
                padding: 0.2rem;
            }
        }

        .video-thumbnail {
            width: 100%;
            max-width: 270px;
            aspect-ratio: 2 / 3;
            object-fit: cover;
        }

    </style>
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css">
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

    <script>
        const player = new Plyr('#player', {
            controls: [
                'play-large', 'play'
                , 'progress', 'current-time'
                , 'mute', 'volume'
                , 'settings', 'fullscreen'
            ]
            , settings: ['quality', 'speed']
        , });

    </script>

    @livewireScripts
    <script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>
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
