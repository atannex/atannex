<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- =========================================
         BASIC META & DOCUMENT SETTINGS
    ========================================== -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Dynamic title with fallback -->
    <title>{{ $title ?? __('Atannex - Lebialem Community News') }}</title>

    <!-- SEO metadata -->
    <meta name="description" content="{{ $description ?? __('Lebialem news and community updates from Atannex') }}">
    <meta name="keywords" content="{{ implode(', ', config('site.keywords')) }}">

    <!-- =========================================
         OPEN GRAPH & TWITTER PREVIEW METADATA
         (Only included when OG variables are set)
    ========================================== -->
    @if(isset($ogTitle))
    <!-- Primary Open Graph tags -->
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="article">
    <meta property="og:image" content="{{ $ogImage }}">

    <!-- Twitter card equivalent -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ogTitle }}">
    <meta name="twitter:description" content="{{ $ogDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    @endif

    <!-- =========================================
         ARTICLE PUBLICATION METADATA
         (Search engines & social networks)
    ========================================== -->
    @isset($publishedAt)
    <meta property="article:published_time" content="{{ $publishedAt }}">
    @endisset

    @isset($updatedAt)
    <meta property="article:modified_time" content="{{ $updatedAt }}">
    @endisset

    <!-- =========================================
         FAVICON SETUP
    ========================================== -->
    @php $favicon = asset('storage/' . ($global['favicon']?->image)); @endphp
    <link rel="icon" href="{{ $favicon }}">
    <link rel="apple-touch-icon" href="{{ $favicon }}">

    <!-- =========================================
         FONT LOADING (Google Fonts optimized)
    ========================================== -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@300;400;600;700&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- =========================================
         ICONS & GLOBAL STYLES
    ========================================== -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">

    <!-- =========================================
         PAGE-SPECIFIC STYLES
         Cache-busted using timestamps
    ========================================== -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/image.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/menu.css') }}?v={{ time() }}">

    @livewireStyles

    <!-- Google Tag Manager -->
    <x-layouts.googletagmanager />

    <!-- Extra styles pushed by individual views -->
    @stack('styles')
</head>

<body>

    <!-- =========================================
         MAIN PAGE CONTENT SLOT
         (Injected from page components)
    ========================================== -->
    {{ $slot }}

    @livewireScripts

    <!-- =========================================
         SCRIPT LIBRARIES & GLOBAL JS
    ========================================== -->

    <!-- jQuery (Slim + Full version—ensure no conflicts) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>

    <script src="{{ asset('assets/js/vendor/jquery-3.6.0.min.js') }}"></script>

    <!-- Core JS bundles -->
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Auth-related scripts -->
    <script src="{{ asset('assets/js/auth/reset.js') }}"></script>

    <!-- Sharing logic -->
    <script src="{{ asset('js/share.js') }}"></script>

    <!-- Extra scripts pushed by pages -->
    @stack('scripts')

</body>
</html>
