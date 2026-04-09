<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" prefix="og: https://ogp.me/ns#">
<head>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <x-layouts.googletagmanager />

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="SAMEORIGIN">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <meta name="format-detection" content="telephone=no, date=no, email=no, address=no">
    <meta name="color-scheme" content="light dark">

    <title>
        @hasSection('title')
        @yield('title')
        @else
        {{ config('app.title', config('app.name')) }}
        @endif
    </title>

    <meta name="description" content="@yield('meta:description', config('app.description'))">
    <meta name="keywords" content="{{ implode(', ', config('site.keywords', [])) }}">
    <meta name="author" content="{{ config('app.organization') }}">
    <meta name="publisher" content="{{ config('app.organization') }}">
    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="generator" content="Laravel {{ app()->version() }} + Custom Enterprise Stack">

    <meta name="robots" content="index, follow, max-image-preview:large, max-video-preview:-1, max-snippet:-1">
    <meta name="googlebot" content="index, follow, max-image-preview:large, max-video-preview:-1, max-snippet:-1">
    <meta name="googlebot-news" content="index, follow">
    <meta name="bingbot" content="index, follow">
    <meta name="slurp" content="index, follow">
    <meta name="msnbot" content="index, follow">
    <link rel="canonical" href="{{ rtrim(url()->current(), '/') }}">

    @php
    $favicon = optional($global['favicon'])->image
    ? asset('storage/' . $global['favicon']->image)
    : asset('favicon/favicon-32x32.png');
    @endphp

    <link rel="icon" href="{{ $favicon }}" type="image/png">
    <link rel="icon" href="{{ asset('favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
    <meta name="theme-color" content="#ffffff">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('favicon/android-chrome-512x512.png') }}">

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">


    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        playfair: ['Playfair Display', 'serif']
                        , dm: ['DM Sans', 'sans-serif']
                    , }
                , }
            , }
        , }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="preconnect" href="https://www.googletagmanager.com" crossorigin>
    <link rel="preconnect" href="https://www.google-analytics.com" crossorigin>

    @verbatim
    <script type="application/ld+json">
        {
            "@context": "https://schema.org"
            , "@type": "Organization"
            , "name": "{{ config('app.name') }}"
            , "url": "{{ config('app.url') }}"
            , "logo": "{{ asset(config('app.image')) }}"
            , "description": "{{ config('app.description') }}"
            , "legalName": "{{ config('app.organization') }}"
            , "sameAs": [
                "{{ config('social.facebook') }}"
                , "{{ config('social.twitter') }}"
                , "{{ config('social.linkedin') }}"
                , "{{ config('social.youtube') }}"
                , "{{ config('social.instagram') }}"
            ]
            , "contactPoint": {
                "@type": "ContactPoint"
                , "telephone": "{{ config('app.phone', '+1-540-242-2572') }}"
                , "contactType": "customer service"
                , "email": "{{ config('app.email') }}"
                , "areaServed": "US"
                , "availableLanguage": ["English"]
            }
            , "address": {
                "@type": "PostalAddress"
                , "streetAddress": "{{ config('app.address.street') }}"
                , "addressLocality": "{{ config('app.address.city') }}"
                , "addressRegion": "{{ config('app.address.state') }}"
                , "postalCode": "{{ config('app.address.zip') }}"
                , "addressCountry": "US"
            }
        }

    </script>

    <script type="application/ld+json">
        {
            "@context": "https://schema.org"
            , "@type": "WebSite"
            , "name": "{{ config('app.name') }}"
            , "url": "{{ config('app.url') }}"
            , "potentialAction": {
                "@type": "SearchAction"
                , "target": "{{ config('app.url') }}/search?q={search_term_string}"
                , "query-input": "required name=search_term_string"
            }
        }

    </script>
    @endverbatim

    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

    @livewireStyles

</head>
<body>

    @yield('content')

    <div id="toast"></div>

    @livewireScripts
    <script src="{{ asset('js/auth.js') }}"></script>

</body>
</html>
