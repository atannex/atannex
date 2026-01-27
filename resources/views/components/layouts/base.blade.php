<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    @include('components.layouts.header')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@300;400;600;700&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}?v=1.0.3">
    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}?v=1.0.3">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v=1.0.3">

    @livewireStyles

    <x-layouts.googletagmanager />

</head>
<body>

    @yield('base')

    @livewireScripts

    <div class="scroll-top">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;">
            </path>
        </svg>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js" defer></script>

    <script src="{{ asset('assets/js/vendor/jquery-3.6.0.min.js') }}?v=1.0.3"></script>
    <script src="{{ asset('assets/js/app.min.js') }}?v=1.0.3"></script>
    <script src="{{ asset('assets/js/main.js') }}?v=1.0.3"></script>
</body>
</html>
