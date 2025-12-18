<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('og:title', config('app.name') . ' - ' . config('app.title'))</title>
    <meta name="description" content="@yield('og:description', config('app.description'))">
    <meta name="keywords" content="{{ implode(', ', config('site.keywords')) }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="robots" content="index, follow">

    <meta property="og:type" content="article">
    <meta property="og:title" content="@yield('og:title', config('app.title'))">
    <meta property="og:description" content="@yield('og:description', config('app.description'))">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:image" content="@yield('og:image', asset(config('app.image')))">
    <meta property="og:locale" content="en_US">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og:title', config('app.title'))">
    <meta name="twitter:description" content="@yield('og:description', config('app.description'))">
    <meta name="twitter:image" content="@yield('og:image', asset(config('app.image')))">
    <meta name="twitter:site" content="@atannex">
    <meta name="twitter:creator" content="@atannex">

    @hasSection('og:publishedAt')
    <meta property="article:published_time" content="@yield('og:publishedAt')">
    @endif

    @hasSection('og:updatedAt')
    <meta property="article:modified_time" content="@yield('og:updatedAt')">
    @endif

    @php
    $favicon = asset('storage/' . optional($global['favicon'])->image);
    @endphp

    <link rel="icon" href="{{ $favicon }}">
    <link rel="apple-touch-icon" href="{{ $favicon }}">
    <meta name="theme-color" content="#ffffff">

    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/site.webmanifest">

    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@300;400;600;700&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/image.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/menu.css') }}">

    @stack('styles')

    @livewireStyles
    <x-layouts.googletagmanager />

</head>

<body>
    @yield('base')

    @livewireScripts

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" defer></script>
    <script src="{{ asset('assets/js/vendor/jquery-3.6.0.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/app.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/main.js') }}" defer></script>
    <script src="{{ asset('assets/js/auth/reset.js') }}" defer></script>
    <script src="{{ asset('js/share.js') }}" defer></script>

    @stack('scripts')

</body>
</html>
