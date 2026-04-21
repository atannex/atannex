<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0f172a">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
    $favicon = optional($global['favicon'])->image
    ? asset('storage/' . $global['favicon']->image)
    : asset('favicon/favicon-32x32.png');

    $pageTitle = trim($__env->yieldContent(
    'title',
    config('app.title') . ' ' . config('app.name')
    ));

    $metaDescription = trim($__env->yieldContent(
    'meta:description',
    config('app.description') ?? 'Default site description'
    ));
    @endphp

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">


    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $favicon }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $favicon }}">

    <link rel="icon" href="{{ $favicon }}" type="image/png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

    @vite(['resources/css/landing/app.css', 'resources/js/landing/app.js'])

    @filamentStyles
    @livewireStyles
</head>

<body class="w-full overflow-x-hidden bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100">

    @include('landing.partials.navbar')

    @yield('content')

    @include('landing.partials.footer')

    @filamentScripts
    @livewireScripts

</body>
</html>
