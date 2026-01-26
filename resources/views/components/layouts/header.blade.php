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
