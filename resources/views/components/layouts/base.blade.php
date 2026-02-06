<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" prefix="og: https://ogp.me/ns#">
<head>
    @php
    $assetVersion = '1.0.3';
    @endphp

    <!-- =========================
         ANALYTICS / TAG MANAGER
    ========================== -->
    <x-layouts.googletagmanager />

    <!-- =========================
         CORE DOCUMENT META
    ========================== -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Security-Policy" content="
    default-src 'self';
    script-src
      'self'
      'unsafe-inline'
      'unsafe-eval'
      https://cdnjs.cloudflare.com
      https://cdn.plyr.io
      https://www.googletagmanager.com
      https://www.google-analytics.com
      https://*.google.com;
    style-src
      'self'
      'unsafe-inline'
      https://fonts.googleapis.com
      https://cdn.plyr.io
      https://cdnjs.cloudflare.com;
    font-src
      'self'
      https://fonts.gstatic.com
      https://cdnjs.cloudflare.com;
    img-src
      'self'
      data:
      blob:
      https:;
    media-src
      'self'
      blob:
      https:
      data:;
    connect-src
      'self'
      blob:
      https://www.google-analytics.com
      https://www.googletagmanager.com;
    frame-src
      'self'
      https://www.googletagmanager.com;
    object-src 'none';
    base-uri 'self';
    form-action 'self';
  ">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="SAMEORIGIN">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <meta name="format-detection" content="telephone=no, date=no, email=no, address=no">
    <meta name="color-scheme" content="light dark">

    <!-- =========================
         APPLICATION IDENTITY & BASIC SEO
    ========================== -->
    <title>
        @hasSection('title')
        @yield('title')
        @else
        {{ config('app.title', config('app.name')) }}
        @endif
    </title>

    <meta name="description" content="@yield('meta:description', config('app.description'))">
    <meta name="keywords" content="{{ implode(', ', config('site.keywords', [])) }}"> <!-- still used by some engines & tools -->
    <meta name="author" content="{{ config('app.organization') }}">
    <meta name="publisher" content="{{ config('app.organization') }}">
    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="generator" content="Laravel {{ app()->version() }} + Custom Enterprise Stack">

    <!-- =========================
         SEARCH ENGINE & CRAWLER DIRECTIVES
    ========================== -->
    <meta name="robots" content="index, follow, max-image-preview:large, max-video-preview:-1, max-snippet:-1">
    <meta name="googlebot" content="index, follow, max-image-preview:large, max-video-preview:-1, max-snippet:-1">
    <meta name="googlebot-news" content="index, follow">
    <meta name="bingbot" content="index, follow">
    <meta name="slurp" content="index, follow"> <!-- Yahoo -->
    <meta name="msnbot" content="index, follow">
    <link rel="canonical" href="{{ rtrim(url()->current(), '/') }}">

    <!-- =========================
         OPEN GRAPH (Facebook, LinkedIn, WhatsApp, etc.)
    ========================== -->
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="@yield('og:type', 'website')">
    <meta property="og:title" content="@yield('og:title', config('app.title'))">
    <meta property="og:description" content="@yield('og:description', config('app.description'))">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:locale" content="en_US">
    <meta property="og:locale:alternate" content="en_GB">
    <meta property="og:image" content="@yield('og:image', asset(config('app.image')))">
    <meta property="og:image:secure_url" content="@yield('og:image', asset(config('app.image')))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="@yield('og:image:alt', config('app.name') . ' preview')">

    @hasSection('og:publishedAt')
    <meta property="article:published_time" content="@yield('og:publishedAt')">
    @endif

    @hasSection('og:updatedAt')
    <meta property="article:modified_time" content="@yield('og:updatedAt')">
    @endif

    @hasSection('og:section')
    <meta property="article:section" content="@yield('og:section')">
    @endif

    @hasSection('og:author')
    <meta property="article:author" content="@yield('og:author')">
    @endif


    <!-- =========================
         TWITTER / X CARDS (still widely used)
    ========================== -->
    <meta name="twitter:card" content="@yield('twitter:card', 'summary_large_image')">
    <meta name="twitter:title" content="@yield('twitter:title', config('app.title'))">
    <meta name="twitter:description" content="@yield('twitter:description', config('app.description'))">
    <meta name="twitter:image" content="@yield('twitter:image', asset(config('app.image')))">
    <meta name="twitter:image:alt" content="@yield('twitter:image:alt', config('app.name') . ' preview')">
    <meta name="twitter:site" content="@atannex">
    <meta name="twitter:creator" content="@atannex">
    <meta name="twitter:domain" content="{{ parse_url(config('app.url'), PHP_URL_HOST) }}">

    @hasSection('twitter:player')
    <meta name="twitter:player" content="@yield('twitter:player')">
    <meta name="twitter:player:width" content="1280">
    <meta name="twitter:player:height" content="720">
    <meta name="twitter:player:stream" content="@yield('twitter:player:stream')">
    @endif

    <!-- =========================
         VIDEO / MEDIA OPEN GRAPH (if needed)
    ========================== -->
    @hasSection('og:video')
    <meta property="og:video" content="@yield('og:video')">
    <meta property="og:video:secure_url" content="@yield('og:video')">
    <meta property="og:video:type" content="video/mp4">
    <meta property="og:video:width" content="1280">
    <meta property="og:video:height" content="720">
    @endif

    <!-- =========================
         ICONS, PWA & APPLE TOUCH (modern / enterprise)
    ========================== -->
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

    <!-- =========================
         PERFORMANCE & RESOURCE HINTS
    ========================== -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="preconnect" href="https://www.googletagmanager.com" crossorigin>
    <link rel="preconnect" href="https://www.google-analytics.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@300;400;600;700&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- =========================
         STRUCTURED DATA (enhanced Organization + WebSite)
    ========================== -->
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

    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.2.0/magnific-popup.min.css">

    <!-- =========================
         STYLES & SCRIPTS
    ========================== -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}?v={{ $assetVersion }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}?v={{ $assetVersion }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ $assetVersion }}">

    @livewireStyles

</head>

<body>

    @yield('base')

    @livewireScripts

    <div class="scroll-top">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" style="transition: stroke-dashoffset 10ms linear; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;">
            </path>
        </svg>
    </div>

    <script src="{{ asset('assets/js/vendor/jquery-3.6.0.min.js') }}?v={{ $assetVersion }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.2.0/jquery.magnific-popup.min.js"></script>
    <script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js.map"></script>
    <script src="{{ asset('assets/js/app.min.js') }}?v={{ $assetVersion }}"></script>
    <script src="{{ asset('assets/js/main.js') }}?v={{ $assetVersion }}"></script>

</body>
</html>
