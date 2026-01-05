<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('og:title', config('app.name') . ' - ' . config('app.title'))</title>
    <meta name="description" content="@yield('og:description', config('app.description'))">
    <meta name="keywords" content="{{ implode(', ', config('site.keywords', [])) }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="@yield('og:type', 'website')">
    <meta property="og:title" content="@yield('og:title', config('app.title'))">
    <meta property="og:description" content="@yield('og:description', config('app.description'))">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:locale" content="en_US">
    <meta property="og:image" content="@yield('og:image', asset(config('app.image')))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    @hasSection('og:publishedAt')
    <meta property="article:published_time" content="@yield('og:publishedAt')">
    @endif

    @hasSection('og:updatedAt')
    <meta property="article:modified_time" content="@yield('og:updatedAt')">
    @endif

    @hasSection('og:video')
    <meta property="og:video" content="@yield('og:video')">
    <meta property="og:video:secure_url" content="@yield('og:video')">
    <meta property="og:video:type" content="video/mp4">
    <meta property="og:video:width" content="1280">
    <meta property="og:video:height" content="720">
    @endif

    <meta name="twitter:card" content="@yield('twitter:card', 'summary_large_image')">
    <meta name="twitter:title" content="@yield('og:title', config('app.title'))">
    <meta name="twitter:description" content="@yield('og:description', config('app.description'))">
    <meta name="twitter:image" content="@yield('og:image', asset(config('app.image')))">
    <meta name="twitter:site" content="@atannex">
    <meta name="twitter:creator" content="@atannex">

    @hasSection('twitter:player')
    <meta name="twitter:player" content="@yield('twitter:player')">
    <meta name="twitter:player:width" content="1280">
    <meta name="twitter:player:height" content="720">
    @endif

    @php
    $favicon = optional($global['favicon'])->image
    ? asset('storage/' . $global['favicon']->image)
    : asset('favicon/favicon-32x32.png');
    @endphp
    <link rel="icon" href="{{ $favicon }}">
    <link rel="apple-touch-icon" href="{{ $favicon }}">
    <meta name="theme-color" content="#ffffff">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@300;400;600;700&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/image.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/video.css') }}">


    @livewireStyles


    {{-- <script type="application/ld+json">
        {
            "@context": "https://schema.org"
            , "@type": "NewsArticle"
            , "mainEntityOfPage": {
                "@type": "WebPage"
                , "@id": "{{ url()->current() }}"
    }
    , "headline": "@yield('og:title', \"{{ config('app.title') }}\")"
    , "image": [
    "@yield('og:image', \"{{ asset(config('app.image')) }}\")"
    ]
    , "datePublished": "@yield('og:publishedAt', \"{{ now()->toIso8601String() }}\")"
    , "dateModified": "@yield('og:updatedAt', \"{{ now()->toIso8601String() }}\")"
    , "author": {
    "@type": "Person"
    , "name": "@yield('article:author', \"{{ config('app.name') }}\")"
    , "url": "@yield('article:author_url', \"{{ config('app.url') }}\")"
    , "sameAs": [
    "@yield('article:author_social', \"{{ config('app.url') }}\")"
    ]
    }
    , "publisher": {
    "@type": "Organization"
    , "name": "{{ config('app.name') }}"
    , "logo": {
    "@type": "ImageObject"
    , "url": "{{ asset('favicon/android-chrome-192x192.png') }}"
    }
    }
    , "description": "@yield('og:description', \"{{ config('app.description') }}\")"
    , "copyrightNotice": "© {{ now()->year }} {{ config('app.name') }}. All rights reserved."
    }

    </script> --}}

    <x-layouts.googletagmanager />

</head>
<body>
    @yield('base')

    @livewireScripts

    <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js" defer></script>

    <script src="{{ asset('assets/js/vendor/jquery-3.6.0.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/app.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/main.js') }}" defer></script>
    <script src="{{ asset('assets/js/auth/reset.js') }}" defer></script>
    <script src="{{ asset('js/share.js') }}" defer></script>
    <script src="{{ asset('assets/js/video.js') }}" defer></script>

</body>
</html>
