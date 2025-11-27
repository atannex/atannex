<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $ogTitle ?? config('app.name') . ' - ' . config('app.title') }}</title>

    <meta name="description" content="{{ $ogDescription ?? config('app.description') }}">
    <meta name="keywords" content="{{ implode(', ', config('site.keywords')) }}">

    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="robots" content="index, follow">

    @if(isset($ogTitle))
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDescription }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:locale" content="en_US">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ogTitle }}">
    <meta name="twitter:description" content="{{ $ogDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    <meta name="twitter:site" content="@atannex">
    <meta name="twitter:creator" content="@atannex">
    @endif

    @isset($publishedAt)
    <meta property="article:published_time" content="{{ $publishedAt }}">
    @endisset

    @isset($updatedAt)
    <meta property="article:modified_time" content="{{ $updatedAt }}">
    @endisset

    @php $favicon = asset('storage/' . ($global['favicon']?->image)); @endphp
    <link rel="icon" href="{{ $favicon }}">
    <link rel="apple-touch-icon" href="{{ $favicon }}">

    <meta name="theme-color" content="#ffffff">

    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@300;400;600;700&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">

    @php $timestamp = time(); @endphp
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ $timestamp }}">
    <link rel="stylesheet" href="{{ asset('assets/css/image.css') }}?v={{ $timestamp }}">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}?v={{ $timestamp }}">
    <link rel="stylesheet" href="{{ asset('assets/css/menu.css') }}?v={{ $timestamp }}">

    @livewireStyles
    <x-layouts.googletagmanager />

</head>

<body>

    {{ $slot }}

    @livewireScripts

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous" defer></script>
    <script src="{{ asset('assets/js/vendor/jquery-3.6.0.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/app.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/main.js') }}" defer></script>
    <script src="{{ asset('assets/js/auth/reset.js') }}" defer></script>
    <script src="{{ asset('js/share.js') }}" defer></script>

</body>
</html>
