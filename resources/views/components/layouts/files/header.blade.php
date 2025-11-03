<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? __('Atannex - Lebialem Community News') }}</title>

    <meta name="description" content="{{ Str::limit($post->description ?? $post->content ?? 'Lebialem community news and updates.', 160) }}">
    <meta name="keywords" content="Lebialem news, Atannex, Cameroon, community news">

    <meta property="og:title" content="{{ $title ?? 'Atannex - Lebialem Community News' }}">
    <meta property="og:description" content="{{ Str::limit($post->description ?? $post->content ?? 'Community updates from Lebialem.', 200) }}">
    <meta property="og:image" content="{{ asset('storage/' . ($global['favicon']?->image ?? 'default-favicon.png')) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="article">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? 'Atannex - Lebialem Community News' }}">
    <meta name="twitter:description" content="{{ Str::limit($post->description ?? $post->content ?? 'Community updates from Lebialem.', 200) }}">
    <meta name="twitter:image" content="{{ asset('storage/' . ($global['favicon']?->image ?? 'default-favicon.png')) }}">

    @if(isset($post))
    <meta property="article:published_time" content="{{ $post->published_at }}">
    <meta property="article:modified_time" content="{{ $post->updated_at }}">
    @endif

    <meta name="theme-color" content="#008000">

    @php $favicon = asset('storage/' . ($global['favicon']?->image ?? 'default-favicon.png')) @endphp
    <link rel="icon" href="{{ $favicon }}">
    <link rel="apple-touch-icon" href="{{ $favicon }}">

    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@300;400;600;700&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/image.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
</head>
