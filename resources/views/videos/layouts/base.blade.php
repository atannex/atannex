<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php

    $pageTitle = trim($__env->yieldContent('title', config('app.title').' '.config('app.name')));

    $videoTitle = trim($__env->yieldContent('video:title', $pageTitle));
    $videoDescription = trim($__env->yieldContent('video:description', config('app.description')));
    $videoThumbnail = trim($__env->yieldContent('video:thumbnail', asset(config('app.image'))));
    $videoUrl = trim($__env->yieldContent('video:url'));
    $videoEmbed = trim($__env->yieldContent('video:embed'));
    $videoPublished = trim($__env->yieldContent('video:published'));
    $videoDuration = trim($__env->yieldContent('video:duration'));
    $videoViews = trim($__env->yieldContent('video:views',0));
    $videoAuthor = trim($__env->yieldContent('video:author', config('app.organization')));

    $metaUrl = url()->current();

    /*
    |--------------------------------------------------------------------------
    | Video Schema
    |--------------------------------------------------------------------------
    */

    $videoSchema = [

    "@context" => "https://schema.org",
    "@type" => "VideoObject",

    "name" => $videoTitle,
    "description" => $videoDescription,

    "thumbnailUrl" => [$videoThumbnail],

    "uploadDate" => $videoPublished,

    "contentUrl" => $videoUrl,
    "embedUrl" => $videoEmbed,

    "duration" => $videoDuration,

    "width" => 1280,
    "height" => 720,

    "interactionStatistic" => [
    "@type" => "InteractionCounter",
    "interactionType" => [
    "@type" => "WatchAction"
    ],
    "userInteractionCount" => (int) $videoViews
    ],

    "publisher" => [
    "@type" => "Organization",
    "name" => config('app.name'),
    "logo" => [
    "@type" => "ImageObject",
    "url" => asset(config('app.image'))
    ]
    ],

    "author" => [
    "@type" => "Person",
    "name" => $videoAuthor
    ],

    "potentialAction" => [
    "@type" => "SeekToAction",
    "target" => $metaUrl . "?t={seek_to_second_number}",
    "startOffset-input" => "required name=seek_to_second_number"
    ],

    "isFamilyFriendly" => true

    ];

    $favicon = optional($global['favicon'])->image
    ? asset('storage/'.$global['favicon']->image)
    : asset('favicon/favicon-32x32.png');

    @endphp


    <title>{{ $pageTitle }}</title>

    <link rel="canonical" href="{{ $metaUrl }}">
    <meta name="robots" content="index, follow">

    <link rel="icon" href="{{ $favicon }}" type="image/png">
    <link rel="icon" href="{{ asset('favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
    <meta name="theme-color" content="#ffffff">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:wght@300;400;500;600;700&family=Barlow+Condensed:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/video-index.css') }}">

    <meta property="og:type" content="video.other">
    <meta property="og:title" content="{{ $videoTitle }}">
    <meta property="og:description" content="{{ $videoDescription }}">
    <meta property="og:url" content="{{ $metaUrl }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">

    <meta property="og:image" content="{{ $videoThumbnail }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <meta property="og:video" content="{{ $videoUrl }}">
    <meta property="og:video:secure_url" content="{{ $videoUrl }}">
    <meta property="og:video:type" content="text/html">
    <meta property="og:video:width" content="1280">
    <meta property="og:video:height" content="720">

    <meta name="twitter:card" content="player">
    <meta name="twitter:title" content="{{ $videoTitle }}">
    <meta name="twitter:description" content="{{ $videoDescription }}">
    <meta name="twitter:image" content="{{ $videoThumbnail }}">

    <meta name="twitter:player" content="{{ $videoEmbed }}">
    <meta name="twitter:player:width" content="1280">
    <meta name="twitter:player:height" content="720">

    <meta name="twitter:site" content="@atannex">
    <meta name="twitter:creator" content="@atannex">

    <script type="application/ld+json">
        @json($videoSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)

    </script>

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">


    @livewireStyles
    @filamentStyles

</head>


<body>

    @yield('base')


    @livewireScripts
    @filamentScripts

    <script src="{{ asset('js/video-index.js') }}"></script>

</body>
</html>
