<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Dynamic Title --}}
    <title>{{ $metaTitle ?? $title ?? __("'Atannex - Lebialem Community News'") }}</title>

    {{-- Basic SEO --}}
    <meta name="description" content="{{ $metaDescription ?? 'Atannex - The trusted news source for the Lebialem community. Stay updated on local news, culture, politics, and events.' }}">
    <meta name="keywords" content="{{ $metaKeywords ?? 'Lebialem news, Atannex, community news, Cameroon, local news, Lebialem culture' }}">
    <meta name="author" content="{{ $metaAuthor ?? 'Atannex Media' }}">

    <!-- Open Graph / Facebook / LinkedIn -->
    <meta property="og:title" content="{{ $globalPost->title ?? 'Atannex - Lebialem Community News' }}">
    <meta property="og:description" content="{{ $globalPost->description ?? 'Stay updated on local news, culture, politics, and events.' }}">
    {{-- <meta property="og:image" content="{{ $globalPost->image ? asset('storage/' . $globalPost->image) : asset('storage/' . $global['favicon']?->image) }}"> --}}
    <meta property="og:url" content="{{ $postUrl ?? url()->current() }}">
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="Atannex - Lebialem Community News">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $globalPost->title ?? 'Atannex - Lebialem Community News' }}">
    <meta name="twitter:description" content="{{ $globalPost->description ?? 'Lebialem news, culture, and community updates' }}">
    {{-- <meta name="twitter:image" content="{{ $globalPost->image ? asset('storage/' . $globalPost->image) : asset('storage/' . $global['favicon']?->image) }}"> --}}
    <meta name="twitter:site" content="@AtannexNews">


    {{-- Google News & Article Metadata --}}
    <meta name="news_keywords" content="{{ $metaKeywords ?? 'Lebialem, Alou, Fontem, Wabane community news' }}">
    <meta property="article:section" content="{{ $metaSection ?? 'Lebialem News' }}">
    <meta property="article:published_time" content="{{ $metaPublished ?? now() }}">
    <meta property="article:modified_time" content="{{ $metaUpdated ?? now() }}">

    {{-- Mobile & Branding --}}
    <meta name="theme-color" content="#008000"> {{-- Atannex brand color (example: green) --}}
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    {{-- Security & Privacy --}}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="referrer" content="no-referrer-when-downgrade">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@300;400;500;600;700;800;900&family=Poppins:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Favicon --}}
    @php $favicon = asset('storage/' . $global['favicon']?->image) @endphp
    <link rel="icon" type="image/png" sizes="96x96" href="{{ $favicon }}">
    <link rel="icon" type="image/svg+xml" href="{{ $favicon }}">
    <link rel="shortcut icon" href="{{ $favicon }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ $favicon }}">

    {{-- Stylesheets --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/image.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
