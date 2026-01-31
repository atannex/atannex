<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php($assetVersion = '1.0.3')

    @include('components.layouts.header')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@300;400;600;700&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        /* Progressive indentation for nested comments */
        .fb-replies {
            margin-left: 50px;
            list-style: none;
            padding-left: 0;
        }

        .fb-replies .fb-replies {
            margin-left: 40px;
        }

        .fb-replies .fb-replies .fb-replies {
            margin-left: 30px;
        }

        .fb-replies .fb-replies .fb-replies .fb-replies {
            margin-left: 20px;
        }

        /* Flatten deep replies (beyond max nesting level) */
        .no-indent {
            margin-left: 0 !important;
            /* border-left: 3px solid #dee2e6; */
            padding-left: 15px;
            /* background: #f8f9fa; */
        }

        .flattened-reply {
            /* background: #f8f9fa; */
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        /* Reply indicator for flattened comments */
        .reply-indicator {
            padding: 5px 10px;
            /* background: #e7f3ff; */
            border-radius: 4px;
            display: inline-block;
            border-left: 3px solid #0d6efd;
        }

        .reply-indicator strong {
            color: #0d6efd;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .fb-replies {
                margin-left: 20px !important;
            }

            .fb-replies .fb-replies {
                margin-left: 15px !important;
            }

            .fb-replies .fb-replies .fb-replies {
                margin-left: 10px !important;
            }

            .fb-replies .fb-replies .fb-replies .fb-replies {
                margin-left: 5px !important;
            }

            .no-indent {
                padding-left: 10px;
            }
        }

        /* Optional: Add subtle animation for nested comments */
        .fb-reply-item {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Replies container */
        .fb-replies {
            list-style: none;
            margin: 6px 0 0 50px;
            padding: 0;
        }

        /* Single reply */
        .fb-reply-item {
            position: relative;
            margin-top: 10px;
        }

        /* Vertical connector line (Facebook feel) */
        .fb-reply-connector {
            position: absolute;
            left: -22px;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: #525457;
        }

        /* Show more / hide */
        .fb-replies-action {
            margin: 8px 0 0 12px;
        }

        .fb-replies-link {
            font-size: 13px;
            color: #1877f2;
            text-decoration: none;
            cursor: pointer;
            font-weight: 500;
        }

        .fb-replies-link:hover {
            text-decoration: underline;
        }

    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}?v={{ $assetVersion }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}?v={{ $assetVersion }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ $assetVersion }}">

    @livewireStyles
    @stack('styles')

    <x-layouts.googletagmanager />
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js" defer></script>

    <script src="{{ asset('assets/js/vendor/jquery-3.6.0.min.js') }}?v={{ $assetVersion }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}?v={{ $assetVersion }}"></script>
    <script src="{{ asset('assets/js/main.js') }}?v={{ $assetVersion }}"></script>

    @stack('scripts')
</body>
</html>
