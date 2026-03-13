<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}" prefix="og: https://ogp.me/ns#">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <x-layouts.googletagmanager />

    @php

    $assetVersion = '1.0.3';

    $pageTitle = trim($__env->yieldContent('title', config('app.title').' '.config('app.name')));

    $metaTitle = trim($__env->yieldContent('og:title', $pageTitle));
    $metaDescription = trim($__env->yieldContent('meta:description', config('app.description')));
    $metaImage = trim($__env->yieldContent('og:image', asset(config('app.image'))));
    $metaUrl = url()->current();

    /*
    |--------------------------------------------------------------------------
    | Schema Data
    |--------------------------------------------------------------------------
    */

    $organizationSchema = [
    "@context" => "https://schema.org",
    "@type" => "Organization",
    "name" => config('app.name'),
    "url" => config('app.url'),
    "logo" => asset(config('app.image'))
    ];

    $articleSchema = [
    "@context" => "https://schema.org",
    "@type" => "NewsArticle",
    "mainEntityOfPage" => [
    "@type" => "WebPage",
    "@id" => $metaUrl
    ],
    "headline" => trim($__env->yieldContent('article:title', $pageTitle)),
    "description" => trim($__env->yieldContent('article:description', $metaDescription)),
    "image" => [
    trim($__env->yieldContent('article:image', $metaImage))
    ],
    "datePublished" => trim($__env->yieldContent('article:published')),
    "dateModified" => trim($__env->yieldContent('article:updated')),
    "author" => [
    "@type" => "Person",
    "name" => trim($__env->yieldContent('article:author', config('app.organization')))
    ],
    "publisher" => [
    "@type" => "Organization",
    "name" => config('app.name'),
    "logo" => [
    "@type" => "ImageObject",
    "url" => asset(config('app.image'))
    ]
    ]
    ];

    $favicon = optional($global['favicon'])->image
    ? asset('storage/'.$global['favicon']->image)
    : asset('favicon/favicon-32x32.png');

    @endphp


    <title>{{ $pageTitle }}</title>

    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="{{ implode(', ', config('site.keywords', [])) }}">
    <meta name="author" content="{{ config('app.organization') }}">
    <meta name="publisher" content="{{ config('app.organization') }}">

    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

    <link rel="canonical" href="{{ rtrim($metaUrl,'/') }}">

    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ $metaUrl }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:locale" content="en_US">

    <meta property="og:image" content="{{ $metaImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <meta property="article:author" content="@yield('article:author')">
    <meta property="article:published_time" content="@yield('article:published')">
    <meta property="article:modified_time" content="@yield('article:updated')">
    <meta property="article:section" content="@yield('article:section')">
    <meta property="article:tag" content="@yield('article:tags')">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $metaImage }}">
    <meta name="twitter:site" content="@atannex">
    <meta name="twitter:creator" content="@yield('article:twitterAuthor','@atannex')">

    <script type="application/ld+json">
        @json($organizationSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)

    </script>

    <script type="application/ld+json">
        @json($articleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)

    </script>

    <link rel="icon" href="{{ $favicon }}" type="image/png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">

    <meta name="theme-color" content="#ffffff">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@300;400;600;700&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css','resources/js/app.js'])

    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}?v={{ $assetVersion }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}?v={{ $assetVersion }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ $assetVersion }}">

    @livewireStyles
    @filamentStyles()

</head>


<body>

    <x-sections.preloader />

    @yield('base')


    @livewireScripts
    @filamentScripts()

    <script src="{{ asset('assets/js/vendor/jquery-3.6.0.min.js') }}?v={{ $assetVersion }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}?v={{ $assetVersion }}"></script>
    <script src="{{ asset('assets/js/main.js') }}?v={{ $assetVersion }}"></script>

</body>
</html>
