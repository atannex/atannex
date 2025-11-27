<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- ===== BASIC META ===== -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title }}</title>

    <meta name="description" content="{{ $description }}">
    <meta name="keywords" content="{{ implode(', ', config('site.keywords')) }}">

    <!-- ===== OPEN GRAPH & TWITTER ===== -->
    @if(isset($ogTitle))
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="article">
    <meta property="og:image" content="{{ $ogImage }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ogTitle }}">
    <meta name="twitter:description" content="{{ $ogDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    @endif

    <!-- ===== ARTICLE TIMESTAMPS ===== -->
    @isset($publishedAt)
    <meta property="article:published_time" content="{{ $publishedAt }}">
    @endisset

    @isset($updatedAt)
    <meta property="article:modified_time" content="{{ $updatedAt }}">
    @endisset

    <!-- ===== FAVICON ===== -->
    @php $favicon = asset('storage/' . ($global['favicon']?->image)); @endphp
    <link rel="icon" href="{{ $favicon }}">
    <link rel="apple-touch-icon" href="{{ $favicon }}">

    <!-- ===== FONTS ===== -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@300;400;600;700&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- ===== ICONS & GLOBAL STYLES ===== -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">

    <!-- ===== PAGE-SPECIFIC STYLES (cache-busted) ===== -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/image.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/menu.css') }}?v={{ time() }}">

    @livewireStyles

    <!-- Google Tag Manager -->
    <x-layouts.googletagmanager />

    @stack('styles')
</head>

<body>

    {{ $slot }}

    @livewireScripts

    <!-- ===== JS LIBRARIES ===== -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>

    <script src="{{ asset('assets/js/vendor/jquery-3.6.0.min.js') }}"></script>

    <!-- ===== MAIN SCRIPTS ===== -->
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/auth/reset.js') }}"></script>
    <script src="{{ asset('js/share.js') }}"></script>

    @stack('scripts')

</body>
</html>
