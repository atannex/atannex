<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0f172a">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
    $favicon = optional($global['favicon'])->image
    ? asset('storage/' . $global['favicon']->image)
    : asset('favicon/favicon-32x32.png');

    $pageTitle = trim($__env->yieldContent(
    'title',
    config('app.title') . ' ' . config('app.name')
    ));

    $metaDescription = trim($__env->yieldContent(
    'meta:description',
    config('app.description') ?? 'Default site description'
    ));
    @endphp

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">


    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $favicon }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $favicon }}">

    <link rel="icon" href="{{ $favicon }}" type="image/png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

    @vite(['resources/css/landing/app.css', 'resources/js/landing/app.js'])

    @filamentStyles
    @livewireStyles
    <style>
        * {
            font-family: 'Outfit', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Space Mono', monospace;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .fade-up {
            animation: fadeUp 0.6s ease-out forwards;
        }

        .fade-scale {
            animation: fadeInScale 0.6s ease-out forwards;
        }

        .delay-100 {
            animation-delay: 0.1s;
        }

        .delay-200 {
            animation-delay: 0.2s;
        }

        .delay-300 {
            animation-delay: 0.3s;
        }

        .glass-effect {
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(148, 163, 184, 0.1);
        }

        .glass-effect:hover {
            background: rgba(30, 41, 59, 0.6);
            border-color: rgba(59, 130, 246, 0.3);
        }

        .card-base {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(71, 85, 105, 0.3);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .card-base:hover {
            background: rgba(30, 41, 59, 0.8);
            border-color: rgba(59, 130, 246, 0.5);
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.1);
        }

        .badge-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            background: rgba(59, 130, 246, 0.1);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .section-title {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 900;
            letter-spacing: -0.02em;
            color: white;
            line-height: 1.1;
        }

        .gradient-text {
            background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
            color: white;
            font-weight: 600;
            border: none;
            padding: 0.875rem 2rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-gradient:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(59, 130, 246, 0.3);
        }

        .btn-outline {
            background: transparent;
            color: #3b82f6;
            font-weight: 600;
            border: 2px solid #3b82f6;
            padding: 0.75rem 1.875rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-outline:hover {
            background: rgba(59, 130, 246, 0.1);
            transform: translateY(-3px);
        }

        .scroll-reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .scroll-reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .scroll-reveal-bottom {
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .scroll-reveal-bottom.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .scroll-reveal-top {
            opacity: 0;
            transform: translateY(-50px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .scroll-reveal-top.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ==================== SCROLLBAR DESIGN ==================== */
        ::-webkit-scrollbar {
            width: 14px;
        }

        ::-webkit-scrollbar-track {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #3b82f6 0%, #06b6d4 50%, #3b82f6 100%);
            border-radius: 10px;
            border: 2px solid #0f172a;
            background-clip: padding-box;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #60a5fa 0%, #22d3ee 50%, #60a5fa 100%);
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
        }

        * {
            scrollbar-color: #3b82f6 #0f172a;
            scrollbar-width: thin;
        }

        .faq-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .faq-content.visible {
            max-height: 500px;
        }

        /* ==================== MODAL NAVBAR STYLES ==================== */
        :root {
            --modal-primary: #3b82f6;
            --modal-primary-light: #60a5fa;
            --modal-secondary: #06b6d4;
            --modal-dark-bg: #0f172a;
            --modal-dark-secondary: #1e293b;
            --modal-text-primary: #ffffff;
            --modal-text-secondary: #cbd5e1;
            --modal-text-tertiary: #94a3b8;
            --modal-border: rgba(148, 163, 184, 0.1);
            --modal-border-light: rgba(148, 163, 184, 0.2);
        }

        /* ==================== HEADER ==================== */
        header.modal-navbar-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 500;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            backdrop-filter: blur(10px);
            background: rgba(15, 23, 42, 0.5);
            border-bottom: 1px solid var(--modal-border);
            animation: slideDown 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (min-width: 768px) {
            header.modal-navbar-header {
                padding: 0 2rem;
            }
        }

        @media (min-width: 1024px) {
            header.modal-navbar-header {
                padding-left: calc(50% - 360px);
                padding-right: calc(50% - 360px);
            }
        }

        .modal-header-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--modal-text-primary);
            font-weight: 800;
            font-size: 1.5rem;
            z-index: 600;
        }

        .modal-logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--modal-primary) 0%, var(--modal-secondary) 100%);
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        /* ==================== NAV TRIGGER BUTTON ==================== */
        .modal-nav-trigger {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--modal-primary) 0%, var(--modal-secondary) 100%);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 12px 30px rgba(59, 130, 246, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            position: relative;
            z-index: 600;
        }

        .modal-nav-trigger:hover {
            transform: scale(1.12);
            box-shadow: 0 16px 40px rgba(59, 130, 246, 0.45), inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .modal-nav-trigger:active {
            transform: scale(0.96);
        }

        /* ==================== MODAL OVERLAY ==================== */
        .modal-nav-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0);
            backdrop-filter: blur(0px);
            z-index: 900;
            opacity: 0;
            pointer-events: none;
            transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .modal-nav-overlay.active {
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(12px);
            opacity: 1;
            pointer-events: auto;
        }

        /* ==================== BOTTOM MODAL NAV ==================== */
        .modal-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 950;
            max-height: 90vh;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.98) 100%);
            border-top: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 2rem 2rem 0 0;
            backdrop-filter: blur(30px);
            transform: translateY(100%);
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 -20px 60px rgba(0, 0, 0, 0.6), inset 0 1px 0 rgba(255, 255, 255, 0.05);
            overflow-y: auto;
            padding-bottom: 3rem;
        }

        .modal-bottom-nav.active {
            transform: translateY(0);
        }

        @media (min-width: 1024px) {
            .modal-bottom-nav {
                left: 50%;
                right: auto;
                width: 85%;
                max-width: 650px;
                border-radius: 2.5rem;
                bottom: 8%;
                transform: translateX(-50%) translateY(100%) scale(0.95);
                box-shadow:
                    0 30px 80px rgba(0, 0, 0, 0.7),
                    0 0 1px rgba(59, 130, 246, 0.2),
                    inset 0 1px 1px rgba(255, 255, 255, 0.08);
                border: 1px solid rgba(59, 130, 246, 0.15);
                max-height: 80vh;
            }

            .modal-bottom-nav.active {
                transform: translateX(-50%) translateY(0) scale(1);
            }
        }


        /* ==================== MODAL NAV HEADER ==================== */
        .modal-nav-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.75rem 2rem;
            border-bottom: 1px solid rgba(59, 130, 246, 0.1);
            position: sticky;
            top: 0;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(20px);
        }

        @media (min-width: 768px) {
            .modal-nav-header {
                padding: 2rem 2.5rem;
            }
        }

        .modal-nav-title {
            font-family: 'Space Mono', monospace;
            font-size: 1.4rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--modal-primary-light) 0%, var(--modal-secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
        }

        .modal-close-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(59, 130, 246, 0.08);
            border: 1px solid rgba(59, 130, 246, 0.15);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--modal-text-primary);
            font-size: 1.25rem;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            padding: 0;
        }

        .modal-close-btn:hover {
            background: rgba(59, 130, 246, 0.15);
            border-color: rgba(59, 130, 246, 0.3);
            transform: rotate(90deg) scale(1.1);
        }

        /* ==================== NAV CONTENT ==================== */
        .modal-nav-content {
            padding: 0;
        }

        /* ==================== NAV SECTIONS ==================== */
        .modal-nav-section {
            padding: 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(59, 130, 246, 0.08);
        }

        @media (min-width: 768px) {
            .modal-nav-section {
                padding: 2rem 2.5rem;
                border-bottom: 1px solid rgba(59, 130, 246, 0.1);
            }
        }

        .modal-nav-section:last-child {
            border-bottom: none;
        }

        .modal-section-title {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--modal-text-tertiary);
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .modal-section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.3), transparent);
        }

        /* ==================== NAV ITEMS ==================== */
        .modal-nav-items {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .modal-nav-item {
            position: relative;
            overflow: hidden;
        }

        .modal-nav-item a {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            padding: 1.1rem 1.5rem;
            color: var(--modal-text-secondary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.98rem;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            border-radius: 0.875rem;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.3px;
        }

        .modal-nav-item a::before {
            content: '';
            position: absolute;
            left: -100%;
            top: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.08), transparent);
            transition: left 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .modal-nav-item:hover a::before {
            left: 100%;
        }

        .modal-nav-item a::after {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 3px;
            height: 100%;
            background: linear-gradient(180deg, var(--modal-primary) 0%, var(--modal-secondary) 100%);
            transform: scaleY(0);
            transform-origin: top;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .modal-nav-item a:hover {
            background: rgba(59, 130, 246, 0.12);
            color: var(--modal-text-primary);
            padding-left: 2rem;
        }

        .modal-nav-item a:hover::after {
            transform: scaleY(1);
        }

        .modal-nav-item-icon {
            font-size: 1.35rem;
            width: 24px;
            text-align: center;
            color: var(--modal-primary);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .modal-nav-item a:hover .modal-nav-item-icon {
            transform: scale(1.15);
            color: var(--modal-secondary);
        }

        /* ==================== CTA SECTION ==================== */
        .modal-nav-cta-section {
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            border-top: 1px solid rgba(59, 130, 246, 0.1);
            background: rgba(59, 130, 246, 0.03);
        }

        @media (min-width: 768px) {
            .modal-nav-cta-section {
                padding: 2.5rem;
                gap: 1.25rem;
            }
        }

        .modal-btn-cta {
            padding: 1.1rem 1.75rem;
            border-radius: 0.875rem;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            border: none;
            cursor: pointer;
            font-size: 0.98rem;
            letter-spacing: 0.3px;
        }

        .modal-btn-primary {
            background: linear-gradient(135deg, var(--modal-primary) 0%, var(--modal-secondary) 100%);
            color: white;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .modal-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(59, 130, 246, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .modal-btn-primary:active {
            transform: translateY(0);
        }

        .modal-btn-secondary {
            background: rgba(59, 130, 246, 0.1);
            color: var(--modal-primary);
            border: 1.5px solid rgba(59, 130, 246, 0.3);
        }

        .modal-btn-secondary:hover {
            background: rgba(59, 130, 246, 0.15);
            border-color: rgba(59, 130, 246, 0.5);
            transform: translateY(-2px);
        }

        /* ==================== INFO SECTION ==================== */
        .modal-nav-info-section {
            padding: 1.75rem 1.5rem;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(6, 182, 212, 0.04) 100%);
            border-radius: 1rem;
            margin: 0 1.5rem;
            border: 1px solid rgba(59, 130, 246, 0.15);
        }

        @media (min-width: 768px) {
            .modal-nav-info-section {
                padding: 2rem 2.5rem;
                margin: 0 2rem;
                border-radius: 1.25rem;
            }
        }

        .modal-info-item {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .modal-info-item:last-child {
            margin-bottom: 0;
        }

        .modal-info-icon {
            font-size: 1.3rem;
            color: var(--modal-primary);
            width: 28px;
            text-align: center;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .modal-info-content {
            flex: 1;
        }

        .modal-info-label {
            font-size: 0.8rem;
            color: var(--modal-text-tertiary);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.35rem;
        }

        .modal-info-value {
            color: var(--modal-text-primary);
            font-size: 0.96rem;
            font-weight: 500;
        }

        /* ==================== SCROLLBAR ==================== */
        .modal-bottom-nav::-webkit-scrollbar {
            display: none;
        }

        .modal-bottom-nav {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        body.modal-nav-open {
            overflow: hidden;
        }

    </style>
</head>

<body class="w-full overflow-x-hidden bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100">

    {{-- ==================== MODAL NAVBAR ==================== --}}
    <header class="modal-navbar-header">
        <a href="{{ route('landing') }}" class="modal-header-logo">
            <div class="modal-logo-icon">
                <i class="fas fa-newspaper"></i>
            </div>
            <span>ATANNEX</span>
        </a>
        <button class="modal-nav-trigger" id="modalNavTrigger" aria-label="Open navigation">
            <i class="fas fa-bars"></i>
        </button>
    </header>

    {{-- MODAL OVERLAY --}}
    <div class="modal-nav-overlay" id="modalNavOverlay"></div>

    {{-- MODAL BOTTOM NAVIGATION --}}
    <nav class="modal-bottom-nav" id="modalBottomNav">
        <div class="modal-nav-header">
            <div class="modal-nav-title">
                <i class="fas fa-compass" style="margin-right: 0.5rem;"></i>Navigation
            </div>
            <button class="modal-close-btn" id="modalNavClose" aria-label="Close navigation">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-nav-content">
            {{-- Main Navigation --}}
            <div class="modal-nav-section">
                <div class="modal-section-title">
                    <i class="fas fa-star"></i>
                    Main Navigation
                </div>
                <ul class="modal-nav-items">
                    <li class="modal-nav-item">
                        <a href="#about">
                            <i class="modal-nav-item-icon fas fa-info-circle"></i>
                            <span>About Us</span>
                        </a>
                    </li>
                    <li class="modal-nav-item">
                        <a href="#stories">
                            <i class="modal-nav-item-icon fas fa-newspaper"></i>
                            <span>Latest Stories</span>
                        </a>
                    </li>
                    <li class="modal-nav-item">
                        <a href="#coverage">
                            <i class="modal-nav-item-icon fas fa-map"></i>
                            <span>Coverage Areas</span>
                        </a>
                    </li>
                    <li class="modal-nav-item">
                        <a href="#contact">
                            <i class="modal-nav-item-icon fas fa-envelope"></i>
                            <span>Contact Us</span>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Content Section --}}
            <div class="modal-nav-section">
                <div class="modal-section-title">
                    <i class="fas fa-align-left"></i>
                    Content
                </div>
                <ul class="modal-nav-items">
                    <li class="modal-nav-item">
                        <a href="{{ route('home') }}">
                            <i class="modal-nav-item-icon fas fa-fire"></i>
                            <span>Featured Stories</span>
                        </a>
                    </li>
                    <li class="modal-nav-item">
                        <a href="#coverage">
                            <i class="modal-nav-item-icon fas fa-video"></i>
                            <span>Video Reports</span>
                        </a>
                    </li>
                    <li class="modal-nav-item">
                        <a href="#archive">
                            <i class="modal-nav-item-icon fas fa-history"></i>
                            <span>Archive</span>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Community Section --}}
            <div class="modal-nav-section">
                <div class="modal-section-title">
                    <i class="fas fa-users"></i>
                    Community
                </div>
                <ul class="modal-nav-items">
                    <li class="modal-nav-item">
                        <a href="/diaspora">
                            <i class="modal-nav-item-icon fas fa-globe"></i>
                            <span>Diaspora Program</span>
                        </a>
                    </li>
                    <li class="modal-nav-item">
                        <a href="/advertise">
                            <i class="modal-nav-item-icon fas fa-bullhorn"></i>
                            <span>Advertise</span>
                        </a>
                    </li>
                    <li class="modal-nav-item">
                        <a href="/careers">
                            <i class="modal-nav-item-icon fas fa-briefcase"></i>
                            <span>Careers</span>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- CTA Section --}}
            <div class="modal-nav-cta-section">
                <a href="/subscribe" class="modal-btn-cta modal-btn-primary">
                    <i class="fas fa-envelope"></i> Subscribe
                </a>
                <a href="/donate" class="modal-btn-cta modal-btn-secondary">
                    <i class="fas fa-heart"></i> Donate
                </a>
            </div>

            {{-- Info Section --}}
            <div class="modal-nav-info-section">
                <div class="modal-info-item">
                    <div class="modal-info-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="modal-info-content">
                        <div class="modal-info-label">Call Us</div>
                        <div class="modal-info-value">+237 690 000 000</div>
                    </div>
                </div>
                <div class="modal-info-item">
                    <div class="modal-info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="modal-info-content">
                        <div class="modal-info-label">Email</div>
                        <div class="modal-info-value">hello@atannex.cm</div>
                    </div>
                </div>
                <div class="modal-info-item">
                    <div class="modal-info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="modal-info-content">
                        <div class="modal-info-label">Location</div>
                        <div class="modal-info-value">Fontem, Lebialem</div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- ==================== HERO SECTION ==================== -->
    <section class="px-6 pt-32 pb-24 md:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="grid items-center gap-16 lg:grid-cols-2">
                <div class="space-y-8">
                    <div class="fade-up">
                        <div class="badge-primary">
                            <i class="fas fa-newspaper"></i>
                            {{ __(' Local Stories. Community Voice.') }}
                        </div>
                    </div>

                    <h1 class="delay-100 section-title fade-up">
                        <span>{{ __('Your Window to') }}</span><br>
                        <span class="gradient-text">{{ __('Lebialem Division') }}</span><br>
                        <span>{{ __('Local Affairs') }}</span>
                    </h1>

                    <p class="max-w-lg text-lg leading-relaxed delay-200 text-slate-300 fade-up">
                        {{ __('Atannex delivers in-depth coverage of community development, grassroots initiatives, and local narratives from Fontem, Alou, and Wabane. Connecting communities. Preserving stories. Building futures.') }}
                    </p>

                    <div class="flex flex-col gap-4 delay-300 sm:flex-row fade-up">
                        <a href="javascript:void(0)" class="btn-gradient">
                            <i class="fas fa-bell"></i> {{ __("Donate") }}
                        </a>
                        <a href="{{ route('home') }}" class="btn-outline">
                            <i class="fas fa-arrow-right"></i> {{ __("Explore") }}
                        </a>
                    </div>

                    <div class="grid grid-cols-3 gap-6 pt-8 delay-300 fade-up">
                        <div class="text-center">
                            <div class="text-3xl font-black md:text-4xl gradient-text">3</div>
                            <p class="mt-1 text-xs font-medium md:text-sm text-slate-400">{{ __('Municipalities') }}</p>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-black md:text-4xl gradient-text">100%</div>
                            <p class="mt-1 text-xs font-medium md:text-sm text-slate-400">{{ __('Local Focus') }}</p>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-black md:text-4xl gradient-text">20K+</div>
                            <p class="mt-1 text-xs font-medium md:text-sm text-slate-400">{{ __('Monthly Readers') }}</p>
                        </div>
                    </div>
                </div>

                <div class="justify-center hidden delay-200 lg:flex fade-up">
                    <img src="{{ asset('storage/' . $global['logo']?->image) }}" alt="{{ config('app.name') }}" class="shadow-2xl rounded-2xl">
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== ABOUT SECTION ==================== -->
    <section id="about" class="px-6 py-24 md:px-8 bg-slate-900/40">
        <div class="mx-auto max-w-7xl">
            <div class="max-w-3xl mx-auto mb-16 text-center scroll-reveal">
                <div class="justify-center mb-4 badge-primary">
                    <i class="fas fa-info-circle"></i> {{ __('About Atannex') }}
                </div>
                <h2 class="mb-4 section-title">{{ __('Who We Are') }}</h2>
                <p class="text-lg text-slate-400">{{ __('A dynamic digital news platform committed to amplifying local voices') }}</p>
            </div>
            <div class="grid gap-8 mb-20 md:grid-cols-3">
                <div class="p-8 rounded-lg card-base scroll-reveal">
                    <div class="flex items-center justify-center mb-4 text-2xl text-blue-400 rounded-lg w-14 h-14 bg-blue-500/20">
                        <i class="fas fa-microphone"></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-white">{{ __('Community Voice') }}</h3>
                    <p class="text-sm leading-relaxed text-slate-400">{{ __('Amplifying underrepresented voices and stories often overlooked by mainstream media outlets.') }}</p>
                </div>
                <div class="p-8 delay-100 rounded-lg card-base scroll-reveal">
                    <div class="flex items-center justify-center mb-4 text-2xl rounded-lg w-14 h-14 bg-cyan-500/20 text-cyan-400">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-white">{{ __('Preserve Heritage') }}</h3>
                    <p class="text-sm leading-relaxed text-slate-400">{{ __('Thoughtfully documenting and preserving the cultural identity and history of Lebialem communities.') }}</p>
                </div>
                <div class="p-8 delay-200 rounded-lg card-base scroll-reveal">
                    <div class="flex items-center justify-center mb-4 text-2xl text-green-400 rounded-lg w-14 h-14 bg-green-500/20">
                        <i class="fas fa-link"></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-white">{{ __('Bridge Connection') }}</h3>
                    <p class="text-sm leading-relaxed text-slate-400">{{ __('Connecting diaspora communities with home, fostering engagement and collaborative development.') }}</p>
                </div>
            </div>

            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div class="scroll-reveal">
                    <h3 class="mb-4 text-3xl font-bold text-white">{{ __('Capturing Lebialem\'s Pulse') }}</h3>
                    <p class="mb-4 leading-relaxed text-slate-400">
                        {{ __('Atannex is a comprehensive community platform dedicated to in-depth coverage of local affairs across Fontem, Alou, and Wabane. We go beyond breaking news to provide balanced, contextual reporting that enhances understanding while maintaining accessibility.') }}
                    </p>
                    <p class="leading-relaxed text-slate-400">
                        {{ __('Our reporting spans grassroots initiatives, leadership activities, education, healthcare efforts, and infrastructural development. We serve as a trusted source where local narratives are not only reported but thoughtfully preserved for future generations.') }}
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4 delay-100 scroll-reveal">
                    <div class="p-6 text-center rounded-lg card-base">
                        <div class="text-3xl font-bold gradient-text">50+</div>
                        <p class="mt-2 text-xs font-medium text-slate-400">{{ __('Stories Published') }}</p>
                    </div>
                    <div class="p-6 text-center rounded-lg card-base">
                        <div class="text-3xl font-bold gradient-text">2K+</div>
                        <p class="mt-2 text-xs font-medium text-slate-400">{{ __('Weekly Visitors') }}</p>
                    </div>
                    <div class="p-6 text-center rounded-lg card-base">
                        <div class="text-3xl font-bold gradient-text">3</div>
                        <p class="mt-2 text-xs font-medium text-slate-400">{{ __('Years of Impact') }}</p>
                    </div>
                    <div class="p-6 text-center rounded-lg card-base">
                        <div class="text-3xl font-bold gradient-text">100%</div>
                        <p class="mt-2 text-xs font-medium text-slate-400">{{ __('Independent') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== COVERAGE AREAS SECTION ==================== -->
    <section id="coverage" class="px-6 py-24 md:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="max-w-3xl mx-auto mb-16 text-center scroll-reveal">
                <div class="justify-center mb-4 badge-primary">
                    <i class="fas fa-map"></i> {{__('Our Focus')}}
                </div>
                <h2 class="mb-4 section-title">{{__('Coverage Areas')}}</h2>
                <p class="text-lg text-slate-400">{{__('In-depth reporting from three vibrant municipalities')}}</p>
            </div>
            <div class="grid gap-8 md:grid-cols-3">
                <div class="p-8 rounded-lg card-base scroll-reveal">
                    <div class="flex items-center justify-center w-12 h-12 mb-4 text-xl text-blue-400 rounded-lg bg-blue-500/20">
                        <i class="fas fa-city"></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-white">{{ __("Fontem") }}</h3>
                    <p class="mb-4 text-sm text-slate-400">{{ __("The divisional headquarters. Reporting on administrative initiatives, civic projects, and community development efforts shaping the future of Lebialem.") }}</p>
                    <div class="space-y-2 text-xs text-slate-300">
                        <p><span class="font-semibold text-blue-400">✓</span>{{__(" Local Government News")}}</p>
                        <p><span class="font-semibold text-blue-400">✓</span> {{ __("Infrastructure Projects") }}</p>
                        <p><span class="font-semibold text-blue-400">✓</span> {{ __("Community Events") }}</p>
                    </div>
                </div>

                <div class="p-8 delay-100 rounded-lg card-base scroll-reveal">
                    <div class="flex items-center justify-center w-12 h-12 mb-4 text-xl rounded-lg bg-cyan-500/20 text-cyan-400">
                        <i class="fas fa-house"></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-white">{{ __("Alou") }}</h3>
                    <p class="mb-4 text-sm text-slate-400">{{ __("Vibrant community stories and grassroots initiatives. Covering education progress, healthcare advances, and local entrepreneurship transforming the municipality.") }}</p>
                    <div class="space-y-2 text-xs text-slate-300">
                        <p><span class="font-semibold text-cyan-400">✓</span> {{ __("Education Updates") }}</p>
                        <p><span class="font-semibold text-cyan-400">✓</span> {{ __("Health Initiatives") }}</p>
                        <p><span class="font-semibold text-cyan-400">✓</span> {{ __("Local Business") }}</p>
                    </div>
                </div>

                <div class="p-8 delay-200 rounded-lg card-base scroll-reveal">
                    <div class="flex items-center justify-center w-12 h-12 mb-4 text-xl text-green-400 rounded-lg bg-green-500/20">
                        <i class="fas fa-tree"></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-white">{{ __("Wabane") }}</h3>
                    <p class="mb-4 text-sm text-slate-400">{{ __("Rural development and cultural stories. Highlighting agricultural advances, community resilience, and the rich traditions defining this unique municipality.") }}</p>
                    <div class="space-y-2 text-xs text-slate-300">
                        <p><span class="font-semibold text-green-400">✓</span> {{ __("Agricultural News") }}</p>
                        <p><span class="font-semibold text-green-400">✓</span> {{ __("Cultural Heritage") }}</p>
                        <p><span class="font-semibold text-green-400">✓</span> {{ __("Rural Development") }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== STORIES SECTION ==================== -->
    <section id="stories" class="px-6 py-24 md:px-8 bg-slate-900/40">
        <div class="mx-auto max-w-7xl">
            <div class="max-w-3xl mx-auto mb-16 text-center scroll-reveal">
                <div class="justify-center mb-4 badge-primary">
                    <i class="fas fa-newspaper"></i> {{ __("Latest Stories") }}
                </div>
                <h2 class="mb-4 section-title">{{ __("Featured Stories") }}</h2>
                <p class="text-lg text-slate-400">{{ __("In-depth coverage of what matters to Lebialem communities") }}</p>
            </div>

            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach($posts as $story)
                <div class="overflow-hidden rounded-lg card-base scroll-reveal group">

                    <div class="h-48 overflow-hidden bg-slate-800">
                        <img src="{{ asset('storage/' . $story->image) }}" alt="{{ $story->title }}" class="object-cover w-full h-full transition duration-500 group-hover:scale-110">
                    </div>

                    <div class="p-6">
                        <div class="mb-2 text-xs font-semibold text-blue-400">
                            {{ $story->category->name }}
                        </div>

                        <h3 class="mb-3 text-lg font-bold text-white">
                            {{ $story->title ? Str::limit($story->title, 60) : __('No title available.') }}
                        </h3>

                        <p class="mb-4 text-sm text-slate-400">
                            {{ $story->description ? Str::limit($story->description, 100) : __('No description available.') }}
                        </p>

                        <a href="{{ route('posts.show', $story->slug_path) }}" class="text-sm font-semibold text-blue-400 transition hover:text-blue-300">
                            {{ __(' Read Story →') }}
                        </a>
                    </div>

                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ==================== EDITORIAL APPROACH SECTION ==================== -->
    <section class="px-6 py-24 md:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="max-w-3xl mx-auto mb-16 text-center scroll-reveal">
                <div class="justify-center mb-4 badge-primary">
                    <i class="fas fa-pen-fancy"></i> {{ __("Our Approach") }}
                </div>
                <h2 class="mb-4 section-title">{{ __("How We Report") }}</h2>
                <p class="text-lg text-slate-400">{{ __("Combining factual reporting with compelling storytelling") }}</p>
            </div>
            <div class="grid gap-8 md:grid-cols-2">
                <div class="p-8 rounded-lg card-base scroll-reveal">
                    <div class="flex items-start gap-4 mb-6">
                        <i class="flex-shrink-0 mt-1 text-3xl text-blue-400 fas fa-lightning"></i>
                        <div>
                            <h3 class="mb-3 text-2xl font-bold text-white">{{ __("Breaking News") }}</h3>
                            <p class="leading-relaxed text-slate-400">{{ __("Timely updates on events affecting our communities, delivered with accuracy and context. We ensure you stay informed about developments as they unfold.") }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 delay-100 rounded-lg card-base scroll-reveal">
                    <div class="flex items-start gap-4 mb-6">
                        <i class="flex-shrink-0 mt-1 text-3xl fas fa-book text-cyan-400"></i>
                        <div>
                            <h3 class="mb-3 text-2xl font-bold text-white">{{ __("Feature Articles") }}</h3>
                            <p class="leading-relaxed text-slate-400">{{ __("In-depth investigations and contextual stories that explore the \"why\" behind the news, offering deeper understanding of community issues and opportunities.") }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 delay-200 rounded-lg card-base scroll-reveal">
                    <div class="flex items-start gap-4 mb-6">
                        <i class="flex-shrink-0 mt-1 text-3xl text-green-400 fas fa-microphone"></i>
                        <div>
                            <h3 class="mb-3 text-2xl font-bold text-white">{{ __("Interviews & Voices") }}</h3>
                            <p class="leading-relaxed text-slate-400">{{ __("Direct conversations with community leaders, innovators, and residents. We amplify diverse perspectives that reflect the richness of our communities.") }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 delay-300 rounded-lg card-base scroll-reveal">
                    <div class="flex items-start gap-4 mb-6">
                        <i class="flex-shrink-0 mt-1 text-3xl text-purple-400 fas fa-comments"></i>
                        <div>
                            <h3 class="mb-3 text-2xl font-bold text-white">{{ __("Community Submissions") }}</h3>
                            <p class="leading-relaxed text-slate-400">{{ __("We welcome stories from community members. Your voices matter, and we provide a platform to share experiences, initiatives, and perspectives.") }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== DIASPORA BRIDGE SECTION ==================== -->
    <section class="px-6 py-24 md:px-8 bg-gradient-to-r from-blue-600 to-cyan-600">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="mb-6 text-4xl font-bold text-white md:text-5xl fade-up">Connecting Diaspora to Home</h2>
            <p class="max-w-2xl mx-auto mb-8 text-lg text-blue-100 delay-100 fade-up">
                Atannex bridges the gap between Lebialem communities and the diaspora living across the globe. Stay connected with your homeland, support local initiatives, and participate in the ongoing narrative of growth and development.
            </p>

            <div class="grid gap-8 mb-12 delay-200 md:grid-cols-3 fade-up">
                <div class="p-6 rounded-lg bg-white/10 backdrop-blur">
                    <div class="mb-2 text-3xl font-bold text-white">Global Reach</div>
                    <p class="text-sm text-blue-100">Readers in 50+ countries stay updated on home developments</p>
                </div>
                <div class="p-6 rounded-lg bg-white/10 backdrop-blur">
                    <div class="mb-2 text-3xl font-bold text-white">Investment Gateway</div>
                    <p class="text-sm text-blue-100">Discover opportunities to invest in your community's future</p>
                </div>
                <div class="p-6 rounded-lg bg-white/10 backdrop-blur">
                    <div class="mb-2 text-3xl font-bold text-white">Collaboration Hub</div>
                    <p class="text-sm text-blue-100">Connect with development initiatives and support causes you believe in</p>
                </div>
            </div>

            <div class="flex flex-col justify-center gap-4 delay-300 sm:flex-row fade-up">
                <a href="/diaspora" class="px-10 py-4 font-bold text-blue-600 transition bg-white rounded-lg hover:bg-blue-50">
                    <i class="fas fa-globe"></i> Diaspora Program
                </a>
                <a href="/subscribe" class="px-10 py-4 font-bold text-white transition border-2 border-white rounded-lg hover:bg-white/10">
                    <i class="fas fa-envelope"></i> Subscribe
                </a>
            </div>
        </div>
    </section>

    <!-- ==================== TESTIMONIALS SECTION ==================== -->
    <section class="px-6 py-24 md:px-8 bg-slate-900/40">
        <div class="mx-auto max-w-7xl">
            <!-- Section Header -->
            <div class="max-w-3xl mx-auto mb-16 text-center scroll-reveal">
                <div class="justify-center mb-4 badge-primary">
                    <i class="fas fa-quote-left"></i> Community Voice
                </div>
                <h2 class="mb-4 section-title">Stories from the Field</h2>
                <p class="text-lg text-slate-400">Real voices sharing the impact of coverage and community initiatives</p>
            </div>

            <!-- Testimonials Grid -->
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                <!-- Testimonial 1 -->
                <div class="flex flex-col p-8 rounded-lg card-base scroll-reveal">
                    <div class="flex gap-1 mb-4">
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                    </div>
                    <p class="flex-grow mb-6 italic leading-relaxed text-slate-300">"Atannex helped us get our school project story heard. The coverage brought attention and support we needed. Finally, our community's voice matters!"</p>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-700/30">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop" alt="Eveline Che" class="object-cover w-12 h-12 rounded-full">
                        <div>
                            <p class="text-sm font-semibold text-white">Eveline Che</p>
                            <p class="text-xs text-slate-400">School Principal, Fontem</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="flex flex-col p-8 delay-100 rounded-lg card-base scroll-reveal">
                    <div class="flex gap-1 mb-4">
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                    </div>
                    <p class="flex-grow mb-6 italic leading-relaxed text-slate-300">"Living in the diaspora, Atannex keeps me connected to home. Their stories help me understand what's happening and where I can contribute to our community's growth."</p>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-700/30">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop" alt="Julius Tanjoh" class="object-cover w-12 h-12 rounded-full">
                        <div>
                            <p class="text-sm font-semibold text-white">Julius Tanjoh</p>
                            <p class="text-xs text-slate-400">Diaspora, UK</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="flex flex-col p-8 delay-200 rounded-lg card-base scroll-reveal">
                    <div class="flex gap-1 mb-4">
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                    </div>
                    <p class="flex-grow mb-6 italic leading-relaxed text-slate-300">"Atannex's coverage of our youth entrepreneurship program was game-changing. The exposure led to partnerships and funding opportunities we wouldn't have found otherwise."</p>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-700/30">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&h=100&fit=crop" alt="Samuel Forbi" class="object-cover w-12 h-12 rounded-full">
                        <div>
                            <p class="text-sm font-semibold text-white">Samuel Forbi</p>
                            <p class="text-xs text-slate-400">Entrepreneur, Alou</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 4 -->
                <div class="flex flex-col p-8 delay-300 rounded-lg card-base scroll-reveal">
                    <div class="flex gap-1 mb-4">
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                    </div>
                    <p class="flex-grow mb-6 italic leading-relaxed text-slate-300">"The agricultural coverage showed our farming methods to the world. This respect for our work has transformed how we see ourselves and our potential."</p>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-700/30">
                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop" alt="Mama Beatrice" class="object-cover w-12 h-12 rounded-full">
                        <div>
                            <p class="text-sm font-semibold text-white">Mama Beatrice</p>
                            <p class="text-xs text-slate-400">Farmer, Wabane</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 5 -->
                <div class="flex flex-col p-8 delay-100 rounded-lg card-base scroll-reveal">
                    <div class="flex gap-1 mb-4">
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                    </div>
                    <p class="flex-grow mb-6 italic leading-relaxed text-slate-300">"As a young journalist, Atannex showed me how powerful local storytelling can be. They're setting the standard for quality coverage in our region."</p>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-700/30">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop" alt="Celestine Ncho" class="object-cover w-12 h-12 rounded-full">
                        <div>
                            <p class="text-sm font-semibold text-white">Celestine Ncho</p>
                            <p class="text-xs text-slate-400">Journalist, Fontem</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 6 -->
                <div class="flex flex-col p-8 delay-200 rounded-lg card-base scroll-reveal">
                    <div class="flex gap-1 mb-4">
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                        <i class="text-sm text-yellow-400 fas fa-star"></i>
                    </div>
                    <p class="flex-grow mb-6 italic leading-relaxed text-slate-300">"Atannex's health campaign coverage saved lives. The awareness they created led to early detection and better health outcomes in our community."</p>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-700/30">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop" alt="Dr. Raphael Tabe" class="object-cover w-12 h-12 rounded-full">
                        <div>
                            <p class="text-sm font-semibold text-white">Dr. Raphael Tabe</p>
                            <p class="text-xs text-slate-400">Health Worker, Alou</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== GLOBAL REACH SECTION ==================== -->
    <section class="px-6 py-24 md:px-8">
        <div class="mx-auto max-w-7xl">
            <!-- Section Header -->
            <div class="max-w-3xl mx-auto mb-16 text-center scroll-reveal">
                <div class="justify-center mb-4 badge-primary">
                    <i class="fas fa-globe"></i> Reach
                </div>
                <h2 class="mb-4 section-title">Our Global Reach</h2>
                <p class="text-lg text-slate-400">Readers across continents staying connected to Lebialem</p>
            </div>

            <!-- Reach Stats Grid -->
            <div class="grid gap-8 mb-12 md:grid-cols-2 lg:grid-cols-4">
                <div class="p-8 text-center rounded-lg card-base scroll-reveal">
                    <div class="mb-2 text-4xl font-bold gradient-text">50+</div>
                    <p class="font-semibold text-white">Countries</p>
                    <p class="mt-2 text-xs text-slate-400">Diaspora readers worldwide</p>
                </div>

                <div class="p-8 text-center delay-100 rounded-lg card-base scroll-reveal">
                    <div class="mb-2 text-4xl font-bold gradient-text">50K+</div>
                    <p class="font-semibold text-white">Monthly Readers</p>
                    <p class="mt-2 text-xs text-slate-400">Growing community engagement</p>
                </div>

                <div class="p-8 text-center delay-200 rounded-lg card-base scroll-reveal">
                    <div class="mb-2 text-4xl font-bold gradient-text">100%</div>
                    <p class="font-semibold text-white">Local Focus</p>
                    <p class="mt-2 text-xs text-slate-400">Dedicated to Lebialem stories</p>
                </div>

                <div class="p-8 text-center delay-300 rounded-lg card-base scroll-reveal">
                    <div class="mb-2 text-4xl font-bold gradient-text">7</div>
                    <p class="font-semibold text-white">Languages</p>
                    <p class="mt-2 text-xs text-slate-400">Accessible to diverse audiences</p>
                </div>
            </div>

            <!-- Regional Breakdown -->
            <div class="grid gap-8 md:grid-cols-3">
                <div class="p-8 rounded-lg card-base scroll-reveal">
                    <div class="flex items-center justify-center w-12 h-12 mb-4 text-xl text-blue-400 rounded-lg bg-blue-500/20">
                        <i class="fas fa-map-location-dot"></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-white">West Africa</h3>
                    <p class="mb-4 text-sm text-slate-400">Strongest readership from Cameroon, Nigeria, and neighboring countries. Active engagement from regional communities.</p>
                    <div class="text-xs font-semibold text-blue-400">35% of traffic</div>
                </div>

                <div class="p-8 delay-100 rounded-lg card-base scroll-reveal">
                    <div class="flex items-center justify-center w-12 h-12 mb-4 text-xl rounded-lg bg-cyan-500/20 text-cyan-400">
                        <i class="fas fa-plane"></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-white">European Diaspora</h3>
                    <p class="mb-4 text-sm text-slate-400">Growing readership from UK, France, Germany, and other European countries. High engagement from diaspora investors.</p>
                    <div class="text-xs font-semibold text-cyan-400">30% of traffic</div>
                </div>

                <div class="p-8 delay-200 rounded-lg card-base scroll-reveal">
                    <div class="flex items-center justify-center w-12 h-12 mb-4 text-xl text-green-400 rounded-lg bg-green-500/20">
                        <i class="fas fa-earth-americas"></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-white">Americas & Others</h3>
                    <p class="mb-4 text-sm text-slate-400">Readers in USA, Canada, and other continents. Building community connections across the globe.</p>
                    <div class="text-xs font-semibold text-green-400">35% of traffic</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== LEADERSHIP SECTION ==================== -->
    <section class="px-6 py-24 md:px-8 bg-slate-900/40">
        <div class="mx-auto max-w-7xl">
            <!-- Section Header -->
            <div class="max-w-3xl mx-auto mb-16 text-center scroll-reveal">
                <div class="justify-center mb-4 badge-primary">
                    <i class="fas fa-people-group"></i> Leadership
                </div>
                <h2 class="mb-4 section-title">Meet Our Team</h2>
                <p class="text-lg text-slate-400">Dedicated journalists and professionals committed to quality local reporting</p>
            </div>

            <!-- Team Grid -->
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                <!-- Team Member 1 -->
                <div class="overflow-hidden rounded-lg card-base scroll-reveal-bottom">
                    <div class="flex items-start gap-4 p-6">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=300&fit=crop" alt="Anteneh Njikam" class="flex-shrink-0 object-cover w-24 h-24 border-2 rounded-full border-blue-400/30">
                        <div class="flex-grow">
                            <h3 class="mb-1 text-lg font-bold text-white">Anteneh Njikam</h3>
                            <p class="mb-3 text-sm font-semibold text-blue-400">Editor-in-Chief & Founder</p>
                            <p class="mb-4 text-xs leading-relaxed text-slate-400">15+ years in journalism. Passionate about amplifying local voices and preserving community heritage. Visionary leader driving Atannex's mission.</p>
                            <div class="flex gap-2">
                                <a href="https://linkedin.com/in/antenehnj" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-8 h-8 text-xs text-blue-400 transition rounded-full bg-blue-500/20 hover:bg-blue-500/40">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                                <a href="https://twitter.com/antenehnj" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-8 h-8 text-xs text-blue-400 transition rounded-full bg-blue-500/20 hover:bg-blue-500/40">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Team Member 2 -->
                <div class="overflow-hidden delay-100 rounded-lg card-base scroll-reveal-bottom">
                    <div class="flex items-start gap-4 p-6">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=300&h=300&fit=crop" alt="Clement Jude" class="flex-shrink-0 object-cover w-24 h-24 border-2 rounded-full border-cyan-400/30">
                        <div class="flex-grow">
                            <h3 class="mb-1 text-lg font-bold text-white">Clement Jude</h3>
                            <p class="mb-3 text-sm font-semibold text-cyan-400">Senior Investigative Journalist</p>
                            <p class="mb-4 text-xs leading-relaxed text-slate-400">12 years covering community development. Expert in uncovering untold stories and connecting grassroots initiatives to broader narratives.</p>
                            <div class="flex gap-2">
                                <a href="https://linkedin.com/in/clementjude" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-8 h-8 text-xs transition rounded-full bg-cyan-500/20 text-cyan-400 hover:bg-cyan-500/40">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                                <a href="https://twitter.com/clementjude" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-8 h-8 text-xs transition rounded-full bg-cyan-500/20 text-cyan-400 hover:bg-cyan-500/40">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Team Member 3 -->
                <div class="overflow-hidden delay-200 rounded-lg card-base scroll-reveal-bottom">
                    <div class="flex items-start gap-4 p-6">
                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=300&h=300&fit=crop" alt="Mirabel Ndambe" class="flex-shrink-0 object-cover w-24 h-24 border-2 rounded-full border-green-400/30">
                        <div class="flex-grow">
                            <h3 class="mb-1 text-lg font-bold text-white">Mirabel Ndambe</h3>
                            <p class="mb-3 text-sm font-semibold text-green-400">Features & Community Correspondent</p>
                            <p class="mb-4 text-xs leading-relaxed text-slate-400">8 years in feature writing. Specializes in human-interest stories that illuminate the resilience and progress of Lebialem communities.</p>
                            <div class="flex gap-2">
                                <a href="https://linkedin.com/in/mirabeln" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-8 h-8 text-xs text-green-400 transition rounded-full bg-green-500/20 hover:bg-green-500/40">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                                <a href="https://twitter.com/mirabeln" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-8 h-8 text-xs text-green-400 transition rounded-full bg-green-500/20 hover:bg-green-500/40">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Team Member 4 -->
                <div class="overflow-hidden delay-300 rounded-lg card-base scroll-reveal-bottom">
                    <div class="flex items-start gap-4 p-6">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=300&h=300&fit=crop" alt="Eveline Pare" class="flex-shrink-0 object-cover w-24 h-24 border-2 rounded-full border-yellow-400/30">
                        <div class="flex-grow">
                            <h3 class="mb-1 text-lg font-bold text-white">Eveline Pare</h3>
                            <p class="mb-3 text-sm font-semibold text-yellow-400">Digital & Diaspora Editor</p>
                            <p class="mb-4 text-xs leading-relaxed text-slate-400">6 years in digital journalism. Bridges diaspora communities with home through compelling multimedia storytelling and engagement strategies.</p>
                            <div class="flex gap-2">
                                <a href="https://linkedin.com/in/evelinepare" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-8 h-8 text-xs text-yellow-400 transition rounded-full bg-yellow-500/20 hover:bg-yellow-500/40">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                                <a href="https://twitter.com/evelinepare" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-8 h-8 text-xs text-yellow-400 transition rounded-full bg-yellow-500/20 hover:bg-yellow-500/40">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Team Member 5 -->
                <div class="overflow-hidden delay-100 rounded-lg card-base scroll-reveal-bottom">
                    <div class="flex items-start gap-4 p-6">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=300&fit=crop" alt="Nkeng Meboto" class="flex-shrink-0 object-cover w-24 h-24 border-2 rounded-full border-purple-400/30">
                        <div class="flex-grow">
                            <h3 class="mb-1 text-lg font-bold text-white">Nkeng Meboto</h3>
                            <p class="mb-3 text-sm font-semibold text-purple-400">Graphics & Multimedia Designer</p>
                            <p class="mb-4 text-xs leading-relaxed text-slate-400">7 years in visual storytelling. Creates compelling graphics and multimedia content that makes complex stories accessible and engaging.</p>
                            <div class="flex gap-2">
                                <a href="https://linkedin.com/in/nkengm" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-8 h-8 text-xs text-purple-400 transition rounded-full bg-purple-500/20 hover:bg-purple-500/40">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                                <a href="https://twitter.com/nkengm" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-8 h-8 text-xs text-purple-400 transition rounded-full bg-purple-500/20 hover:bg-purple-500/40">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Team Member 6 -->
                <div class="overflow-hidden delay-200 rounded-lg card-base scroll-reveal-bottom">
                    <div class="flex items-start gap-4 p-6">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=300&h=300&fit=crop" alt="Tanoh Kefack" class="flex-shrink-0 object-cover w-24 h-24 border-2 rounded-full border-red-400/30">
                        <div class="flex-grow">
                            <h3 class="mb-1 text-lg font-bold text-white">Tanoh Kefack</h3>
                            <p class="mb-3 text-sm font-semibold text-red-400">Community Relations Manager</p>
                            <p class="mb-4 text-xs leading-relaxed text-slate-400">5 years in community engagement. Builds relationships with sources, organizations, and readers to ensure Atannex remains community-centered.</p>
                            <div class="flex gap-2">
                                <a href="https://linkedin.com/in/tanohk" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-8 h-8 text-xs text-red-400 transition rounded-full bg-red-500/20 hover:bg-red-500/40">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                                <a href="https://twitter.com/tanohk" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-8 h-8 text-xs text-red-400 transition rounded-full bg-red-500/20 hover:bg-red-500/40">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== PARTNERS SECTION ==================== -->
    <section class="px-6 py-24 md:px-8">
        <div class="mx-auto max-w-7xl">
            <!-- Section Header -->
            <div class="max-w-3xl mx-auto mb-16 text-center scroll-reveal">
                <div class="justify-center mb-4 badge-primary">
                    <i class="fas fa-handshake"></i> Partners
                </div>
                <h2 class="mb-4 section-title">Partners & Supporters</h2>
                <p class="text-lg text-slate-400">Organizations supporting quality journalism in Lebialem</p>
            </div>

            <!-- Partners Grid -->
            <div class="grid gap-8 mb-12 md:grid-cols-2 lg:grid-cols-4">
                <div class="flex items-center justify-center h-32 p-8 transition rounded-lg card-base scroll-reveal hover:scale-105">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/ee/UN_emblem_blue.svg/1024px-UN_emblem_blue.svg.png" alt="United Nations" class="object-contain w-auto h-20">
                </div>

                <div class="flex items-center justify-center h-32 p-8 transition delay-100 rounded-lg card-base scroll-reveal hover:scale-105">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5f/UNICEF_Logo.svg/1024px-UNICEF_Logo.svg.png" alt="UNICEF" class="object-contain w-auto h-20">
                </div>

                <div class="flex items-center justify-center h-32 p-8 transition delay-200 rounded-lg card-base scroll-reveal hover:scale-105">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/84/World_Bank_logo.svg/1024px-World_Bank_logo.svg.png" alt="World Bank" class="object-contain w-auto h-20">
                </div>

                <div class="flex items-center justify-center h-32 p-8 transition delay-300 rounded-lg card-base scroll-reveal hover:scale-105">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9f/Oxfam_International_Logo.svg/1024px-Oxfam_International_Logo.svg.png" alt="Oxfam" class="object-contain w-auto h-20">
                </div>
            </div>

            <!-- Secondary Partners -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-5">
                <div class="flex items-center justify-center p-6 transition rounded-lg card-base h-28 scroll-reveal hover:scale-105">
                    <p class="text-sm font-semibold text-center text-slate-400">Cameroonian Media Association</p>
                </div>
                <div class="flex items-center justify-center p-6 transition delay-100 rounded-lg card-base h-28 scroll-reveal hover:scale-105">
                    <p class="text-sm font-semibold text-center text-slate-400">SW Regional Government</p>
                </div>
                <div class="flex items-center justify-center p-6 transition delay-200 rounded-lg card-base h-28 scroll-reveal hover:scale-105">
                    <p class="text-sm font-semibold text-center text-slate-400">Local NGOs Coalition</p>
                </div>
                <div class="flex items-center justify-center p-6 transition delay-300 rounded-lg card-base h-28 scroll-reveal hover:scale-105">
                    <p class="text-sm font-semibold text-center text-slate-400">Community Leaders Forum</p>
                </div>
                <div class="flex items-center justify-center p-6 transition delay-100 rounded-lg card-base h-28 scroll-reveal hover:scale-105">
                    <p class="text-sm font-semibold text-center text-slate-400">Educational Institutions</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== ENHANCED GALLERY SECTION ==================== -->
    <section class="px-6 py-24 md:px-8 bg-slate-900/40">
        <div class="mx-auto max-w-7xl">
            <!-- Section Header -->
            <div class="max-w-3xl mx-auto mb-16 text-center scroll-reveal">
                <div class="justify-center mb-4 badge-primary">
                    <i class="fas fa-images"></i> Gallery
                </div>
                <h2 class="mb-4 section-title">Visual Stories Gallery</h2>
                <p class="text-lg text-slate-400">Moments capturing the spirit and progress of Lebialem communities</p>
            </div>

            <!-- Enhanced Gallery Grid -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <div class="relative overflow-hidden rounded-lg cursor-pointer group h-72 scroll-reveal">
                    <img src="https://images.unsplash.com/photo-1427504494785-cdbed0c3675b?w=500&h=500&fit=crop" alt="Education" class="object-cover w-full h-full transition duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 flex items-center justify-center transition bg-black/30 group-hover:bg-black/50">
                        <div class="text-center transition opacity-0 group-hover:opacity-100">
                            <p class="font-semibold text-white">School Programs</p>
                            <p class="text-sm text-blue-300">Education Initiatives</p>
                        </div>
                    </div>
                </div>

                <div class="relative overflow-hidden delay-100 rounded-lg cursor-pointer group h-72 scroll-reveal">
                    <img src="https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?w=500&h=500&fit=crop" alt="Healthcare" class="object-cover w-full h-full transition duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 flex items-center justify-center transition bg-black/30 group-hover:bg-black/50">
                        <div class="text-center transition opacity-0 group-hover:opacity-100">
                            <p class="font-semibold text-white">Health Campaigns</p>
                            <p class="text-sm text-green-300">Community Wellness</p>
                        </div>
                    </div>
                </div>

                <div class="relative overflow-hidden delay-200 rounded-lg cursor-pointer group h-72 scroll-reveal">
                    <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=500&h=500&fit=crop" alt="Community" class="object-cover w-full h-full transition duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 flex items-center justify-center transition bg-black/30 group-hover:bg-black/50">
                        <div class="text-center transition opacity-0 group-hover:opacity-100">
                            <p class="font-semibold text-white">Community Events</p>
                            <p class="text-sm text-cyan-300">Local Gatherings</p>
                        </div>
                    </div>
                </div>

                <div class="relative overflow-hidden delay-300 rounded-lg cursor-pointer group h-72 scroll-reveal">
                    <img src="https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=500&h=500&fit=crop" alt="Agriculture" class="object-cover w-full h-full transition duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 flex items-center justify-center transition bg-black/30 group-hover:bg-black/50">
                        <div class="text-center transition opacity-0 group-hover:opacity-100">
                            <p class="font-semibold text-white">Agriculture</p>
                            <p class="text-sm text-yellow-300">Farming Progress</p>
                        </div>
                    </div>
                </div>

                <div class="relative overflow-hidden delay-100 rounded-lg cursor-pointer group h-72 scroll-reveal">
                    <img src="https://images.unsplash.com/photo-1511379938547-c1f69b13d835?w=500&h=500&fit=crop" alt="Culture" class="object-cover w-full h-full transition duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 flex items-center justify-center transition bg-black/30 group-hover:bg-black/50">
                        <div class="text-center transition opacity-0 group-hover:opacity-100">
                            <p class="font-semibold text-white">Cultural Heritage</p>
                            <p class="text-sm text-purple-300">Traditions & Celebrations</p>
                        </div>
                    </div>
                </div>

                <div class="relative overflow-hidden delay-200 rounded-lg cursor-pointer group h-72 scroll-reveal">
                    <img src="https://images.unsplash.com/photo-1590509780387-da94e08b3060?w=500&h=500&fit=crop" alt="Infrastructure" class="object-cover w-full h-full transition duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 flex items-center justify-center transition bg-black/30 group-hover:bg-black/50">
                        <div class="text-center transition opacity-0 group-hover:opacity-100">
                            <p class="font-semibold text-white">Infrastructure</p>
                            <p class="text-sm text-red-300">Development Projects</p>
                        </div>
                    </div>
                </div>

                <div class="relative overflow-hidden delay-300 rounded-lg cursor-pointer group h-72 scroll-reveal">
                    <img src="https://images.unsplash.com/photo-1504711331512-be2a21fb4557?w=500&h=500&fit=crop" alt="Youth" class="object-cover w-full h-full transition duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 flex items-center justify-center transition bg-black/30 group-hover:bg-black/50">
                        <div class="text-center transition opacity-0 group-hover:opacity-100">
                            <p class="font-semibold text-white">Youth Initiatives</p>
                            <p class="text-sm text-blue-300">Future Leaders</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== FAQ SECTION ==================== -->
    <section class="px-6 py-24 md:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Section Header -->
            <div class="max-w-3xl mx-auto mb-16 text-center scroll-reveal">
                <div class="justify-center mb-4 badge-primary">
                    <i class="fas fa-circle-question"></i> Questions
                </div>
                <h2 class="mb-4 section-title">Frequently Asked Questions</h2>
            </div>

            <!-- FAQ Two Columns -->
            <div class="grid gap-8 md:grid-cols-2">
                <!-- Left Column -->
                <div class="space-y-4">
                    <!-- FAQ 1 -->
                    <div class="overflow-hidden rounded-lg faq-item card-base scroll-reveal">
                        <button class="flex items-center justify-between w-full p-6 text-left transition faq-toggle hover:bg-slate-800/50">
                            <h3 class="text-lg font-semibold text-white">How often do you publish stories?</h3>
                            <i class="text-blue-400 transition-transform duration-300 fas fa-chevron-down"></i>
                        </button>
                        <div class="px-6 pb-6 text-sm leading-relaxed faq-content text-slate-400">
                            We publish breaking news daily and feature stories multiple times per week. Our newsroom monitors developments across Fontem, Alou, and Wabane to bring you timely, relevant coverage of community affairs.
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="overflow-hidden delay-100 rounded-lg faq-item card-base scroll-reveal">
                        <button class="flex items-center justify-between w-full p-6 text-left transition faq-toggle hover:bg-slate-800/50">
                            <h3 class="text-lg font-semibold text-white">Can I submit a story?</h3>
                            <i class="text-blue-400 transition-transform duration-300 fas fa-chevron-down"></i>
                        </button>
                        <div class="px-6 pb-6 text-sm leading-relaxed faq-content text-slate-400">
                            Absolutely! We welcome community submissions. Email us at hello@atannex.cm with your story ideas, tips, or community announcements. Our editorial team reviews all submissions for publication consideration.
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="overflow-hidden delay-200 rounded-lg faq-item card-base scroll-reveal">
                        <button class="flex items-center justify-between w-full p-6 text-left transition faq-toggle hover:bg-slate-800/50">
                            <h3 class="text-lg font-semibold text-white">How can I subscribe?</h3>
                            <i class="text-blue-400 transition-transform duration-300 fas fa-chevron-down"></i>
                        </button>
                        <div class="px-6 pb-6 text-sm leading-relaxed faq-content text-slate-400">
                            Visit our subscribe page to sign up for our newsletter. You'll receive curated stories delivered to your inbox, keeping you updated on what's happening in Lebialem wherever you are in the world.
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <!-- FAQ 4 -->
                    <div class="overflow-hidden delay-300 rounded-lg faq-item card-base scroll-reveal">
                        <button class="flex items-center justify-between w-full p-6 text-left transition faq-toggle hover:bg-slate-800/50">
                            <h3 class="text-lg font-semibold text-white">Are you independent journalism?</h3>
                            <i class="text-blue-400 transition-transform duration-300 fas fa-chevron-down"></i>
                        </button>
                        <div class="px-6 pb-6 text-sm leading-relaxed faq-content text-slate-400">
                            Yes. Atannex is 100% independent and community-focused. We're committed to fair, balanced reporting that serves the interests of Lebialem communities rather than any single political or commercial entity.
                        </div>
                    </div>

                    <!-- FAQ 5 -->
                    <div class="overflow-hidden delay-100 rounded-lg faq-item card-base scroll-reveal">
                        <button class="flex items-center justify-between w-full p-6 text-left transition faq-toggle hover:bg-slate-800/50">
                            <h3 class="text-lg font-semibold text-white">How do you cover diaspora interests?</h3>
                            <i class="text-blue-400 transition-transform duration-300 fas fa-chevron-down"></i>
                        </button>
                        <div class="px-6 pb-6 text-sm leading-relaxed faq-content text-slate-400">
                            Our diaspora program features investment opportunities, community development initiatives, and cultural preservation stories that keep our global readers connected to Lebialem and engaged in its progress.
                        </div>
                    </div>

                    <!-- FAQ 6 -->
                    <div class="overflow-hidden delay-200 rounded-lg faq-item card-base scroll-reveal">
                        <button class="flex items-center justify-between w-full p-6 text-left transition faq-toggle hover:bg-slate-800/50">
                            <h3 class="text-lg font-semibold text-white">How can I advertise with Atannex?</h3>
                            <i class="text-blue-400 transition-transform duration-300 fas fa-chevron-down"></i>
                        </button>
                        <div class="px-6 pb-6 text-sm leading-relaxed faq-content text-slate-400">
                            We offer advertising opportunities for local and diaspora businesses. Contact our advertising team at hello@atannex.cm or visit our advertise page to learn about rates and reach our engaged readership.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== GRAND CTA SECTION ==================== -->
    <section class="relative px-6 py-32 overflow-hidden md:px-8 bg-gradient-to-r from-blue-600 via-cyan-600 to-blue-600">
        <!-- Animated background -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-0 left-0 bg-white rounded-full w-96 h-96 mix-blend-screen blur-3xl animate-pulse"></div>
            <div class="absolute bottom-0 right-0 bg-white rounded-full w-96 h-96 mix-blend-screen blur-3xl animate-pulse"></div>
        </div>

        <div class="relative z-10 max-w-5xl mx-auto text-center">
            <h2 class="mb-8 text-5xl font-bold text-white md:text-6xl fade-up">Join Atannex Community</h2>

            <p class="max-w-3xl mx-auto mb-4 text-xl leading-relaxed text-blue-100 delay-100 fade-up">
                Be part of a movement preserving and amplifying Lebialem's stories. Stay informed, connected, and engaged with your community.
            </p>

            <p class="mb-12 text-lg delay-200 text-blue-50 fade-up">
                <i class="mr-2 fas fa-newspaper"></i>
                <span class="font-semibold">Quality local journalism you can trust</span>
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col justify-center gap-6 mb-16 delay-300 sm:flex-row fade-up">
                <a href="/subscribe" class="flex items-center justify-center gap-2 px-10 py-4 text-lg font-bold text-blue-600 transition transform bg-white rounded-lg hover:bg-blue-50 hover:scale-105">
                    <i class="fas fa-bell"></i> Subscribe Now
                </a>
                <a href="mailto:hello@atannex.cm" class="flex items-center justify-center gap-2 px-10 py-4 text-lg font-bold text-white transition transform border-white rounded-lg border-3 hover:bg-white/10 hover:scale-105">
                    <i class="fas fa-envelope"></i> Contact Us
                </a>
                <a href="/diaspora" class="flex items-center justify-center gap-2 px-10 py-4 text-lg font-bold text-white transition transform border-white rounded-lg border-3 hover:bg-white/10 hover:scale-105">
                    <i class="fas fa-globe"></i> Diaspora Program
                </a>
            </div>

            <!-- Stats Highlight -->
            <div class="grid max-w-3xl gap-8 mx-auto mb-16 md:grid-cols-3 fade-up delay-400">
                <div>
                    <div class="mb-2 text-4xl font-bold text-white">500+</div>
                    <p class="text-sm text-blue-100">Stories Published</p>
                </div>
                <div>
                    <div class="mb-2 text-4xl font-bold text-white">50+</div>
                    <p class="text-sm text-blue-100">Countries Reached</p>
                </div>
                <div>
                    <div class="mb-2 text-4xl font-bold text-white">100%</div>
                    <p class="text-sm text-blue-100">Community-Focused</p>
                </div>
            </div>

            <!-- Social Links -->
            <div class="flex justify-center gap-8 delay-500 fade-up">
                <a href="https://facebook.com/atannex" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-12 h-12 text-white transition rounded-full bg-white/20 hover:bg-white/30 hover:scale-110">
                    <i class="text-xl fab fa-facebook"></i>
                </a>
                <a href="https://twitter.com/atannex" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-12 h-12 text-white transition rounded-full bg-white/20 hover:bg-white/30 hover:scale-110">
                    <i class="text-xl fab fa-twitter"></i>
                </a>
                <a href="https://instagram.com/atannex" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-12 h-12 text-white transition rounded-full bg-white/20 hover:bg-white/30 hover:scale-110">
                    <i class="text-xl fab fa-instagram"></i>
                </a>
                <a href="https://youtube.com/atannex" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-12 h-12 text-white transition rounded-full bg-white/20 hover:bg-white/30 hover:scale-110">
                    <i class="text-xl fab fa-youtube"></i>
                </a>
            </div>
        </div>
    </section>

    <section id="contact" class="px-6 py-24 md:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="max-w-3xl mx-auto mb-16 text-center scroll-reveal">
                <div class="justify-center mb-4 badge-primary">
                    <i class="fas fa-envelope"></i> Get In Touch
                </div>
                <h2 class="mb-4 section-title">Contact Us</h2>
                <p class="text-lg text-slate-400">We'd love to hear from you. Share tips, stories, or feedback.</p>
            </div>

            <div class="grid gap-8 mb-12 md:grid-cols-3">
                <div class="p-6 text-center rounded-lg card-base scroll-reveal">
                    <i class="mb-4 text-3xl text-blue-400 fas fa-envelope"></i>
                    <h3 class="mb-2 font-bold text-white">Email</h3>
                    <a href="mailto:hello@atannex.cm" class="text-blue-400 hover:text-blue-300">hello@atannex.cm</a>
                </div>

                <div class="p-6 text-center delay-100 rounded-lg card-base scroll-reveal">
                    <i class="mb-4 text-3xl fas fa-phone text-cyan-400"></i>
                    <h3 class="mb-2 font-bold text-white">Call</h3>
                    <a href="tel:+237690000000" class="text-blue-400 hover:text-blue-300">+237 690 000 000</a>
                </div>

                <div class="p-6 text-center delay-200 rounded-lg card-base scroll-reveal">
                    <i class="mb-4 text-3xl text-green-400 fas fa-map-marker-alt"></i>
                    <h3 class="mb-2 font-bold text-white">Fontem, Lebialem</h3>
                    <p class="text-sm text-slate-400">Lebialem Division, South-West Region, Cameroon</p>
                </div>
            </div>

            <!-- Social Links -->
            <div class="text-center scroll-reveal">
                <p class="mb-6 text-slate-400">Follow us on social media for daily updates</p>
                <div class="flex justify-center gap-6">
                    <a href="https://facebook.com/atannex" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-12 h-12 text-blue-400 transition rounded-full bg-blue-500/20 hover:bg-blue-500/40 hover:scale-110">
                        <i class="text-xl fab fa-facebook"></i>
                    </a>
                    <a href="https://twitter.com/atannex" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-12 h-12 text-blue-400 transition rounded-full bg-blue-500/20 hover:bg-blue-500/40 hover:scale-110">
                        <i class="text-xl fab fa-twitter"></i>
                    </a>
                    <a href="https://instagram.com/atannex" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-12 h-12 text-blue-400 transition rounded-full bg-blue-500/20 hover:bg-blue-500/40 hover:scale-110">
                        <i class="text-xl fab fa-instagram"></i>
                    </a>
                    <a href="https://youtube.com/atannex" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-12 h-12 text-blue-400 transition rounded-full bg-blue-500/20 hover:bg-blue-500/40 hover:scale-110">
                        <i class="text-xl fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <footer class="px-6 py-12 border-t md:px-8 border-slate-700/20">
        <div class="mx-auto max-w-7xl">
            <div class="grid gap-8 mb-8 md:grid-cols-4">
                <div>
                    <h4 class="mb-4 font-bold text-white">Atannex</h4>
                    <p class="text-sm text-slate-400">Digital news platform covering Lebialem Division's local affairs, community stories, and development initiatives.</p>
                </div>
                <div>
                    <h4 class="mb-4 font-bold text-white">Content</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="/stories" class="transition hover:text-white">Stories</a></li>
                        <li><a href="#coverage" class="transition hover:text-white">Coverage</a></li>
                        <li><a href="/diaspora" class="transition hover:text-white">Diaspora</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="mb-4 font-bold text-white">Company</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="#about" class="transition hover:text-white">About</a></li>
                        <li><a href="/advertise" class="transition hover:text-white">Advertise</a></li>
                        <li><a href="/careers" class="transition hover:text-white">Careers</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="mb-4 font-bold text-white">Legal</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="/privacy" class="transition hover:text-white">Privacy</a></li>
                        <li><a href="/terms" class="transition hover:text-white">Terms</a></li>
                        <li><a href="mailto:hello@atannex.cm" class="transition hover:text-white">Contact</a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 text-sm text-center border-t border-slate-700/20 text-slate-400">
                <p>&copy; 2024 Atannex. All rights reserved. | Capturing Lebialem's Stories for the World</p>
            </div>
        </div>
    </footer>

    <script>
        // Scroll Reveal
        const observerOptions = {
            threshold: 0.1
            , rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    if (entry.target.classList.contains('scroll-reveal')) {
                        entry.target.classList.add('visible');
                    }
                    if (entry.target.classList.contains('scroll-reveal-bottom')) {
                        entry.target.classList.add('visible');
                    }
                    if (entry.target.classList.contains('scroll-reveal-top')) {
                        entry.target.classList.add('visible');
                    }
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.scroll-reveal, .scroll-reveal-bottom, .scroll-reveal-top').forEach(el => {
            observer.observe(el);
        });

        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href !== '#') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth'
                            , block: 'start'
                        });
                    }
                }
            });
        });

        // ==================== MODAL NAV FUNCTIONALITY ====================
        const navTrigger = document.getElementById('modalNavTrigger');
        const navClose = document.getElementById('modalNavClose');
        const modalNav = document.getElementById('modalBottomNav');
        const modalOverlay = document.getElementById('modalNavOverlay');
        const navItems = document.querySelectorAll('.modal-nav-item a');
        const body = document.body;

        if (navTrigger && modalNav) {
            function openModal() {
                modalNav.classList.add('active');
                modalOverlay.classList.add('active');
                body.classList.add('modal-nav-open');
                navTrigger.style.pointerEvents = 'none';
            }

            function closeModal() {
                modalNav.classList.remove('active');
                modalOverlay.classList.remove('active');
                body.classList.remove('modal-nav-open');
                navTrigger.style.pointerEvents = 'auto';
            }

            navTrigger.addEventListener('click', openModal);
            navClose.addEventListener('click', closeModal);
            modalOverlay.addEventListener('click', closeModal);

            navItems.forEach(item => {
                item.addEventListener('click', closeModal);
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && modalNav.classList.contains('active')) {
                    closeModal();
                }
            });

            modalNav.addEventListener('touchmove', (e) => {
                e.stopPropagation();
            }, {
                passive: true
            });
        }

    </script>

    @filamentScripts
    @livewireScripts

</body>
</html>
