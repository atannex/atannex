<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}" prefix="og: https://ogp.me/ns#">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php

    $favicon = optional($global['favicon'])->image
    ? asset('storage/'.$global['favicon']->image)
    : asset('favicon/favicon-32x32.png');

    @endphp

    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

    <link rel="icon" href="{{ $favicon }}" type="image/png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1a1f3a 100%);
            color: #e2e8f0;
            font-family: 'Courier New', 'Monaco', 'Consolas', monospace;
            overflow-x: hidden;
            font-size: 15px;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(20px) rotate(0.5deg);
            }
        }

        @keyframes pulse-glow {

            0%,
            100% {
                box-shadow: 0 0 20px rgba(14, 165, 233, 0.3);
            }

            50% {
                box-shadow: 0 0 40px rgba(14, 165, 233, 0.5);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes breathe {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-25px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.92);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(25px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes staggerFadeInUp {
            from {
                opacity: 0;
                transform: translateY(35px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }

            100% {
                background-position: 1000px 0;
            }
        }

        @keyframes glow-pulse {

            0%,
            100% {
                opacity: 0.6;
                box-shadow: 0 0 20px rgba(14, 165, 233, 0.3);
            }

            50% {
                opacity: 1;
                box-shadow: 0 0 40px rgba(14, 165, 233, 0.6);
            }
        }

        @keyframes floatUp {
            0% {
                opacity: 0;
                transform: translateY(40px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInDownElastic {
            from {
                opacity: 0;
                transform: translateY(-40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes rotateInScale {
            from {
                opacity: 0;
                transform: scale(0.5) rotate(-5deg);
            }

            to {
                opacity: 1;
                transform: scale(1) rotate(0deg);
            }
        }

        @keyframes expandWidth {
            from {
                opacity: 0;
                width: 0;
            }

            to {
                opacity: 1;
                width: 100%;
            }
        }

        @keyframes fadeInBlur {
            from {
                opacity: 0;
                filter: blur(10px);
            }

            to {
                opacity: 1;
                filter: blur(0);
            }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        .pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }

        .slide-in-up {
            animation: slideInUp 0.8s ease-out forwards;
        }

        .slide-in-left {
            animation: slideInLeft 0.8s ease-out forwards;
        }

        .slide-in-right {
            animation: slideInRight 0.8s ease-out forwards;
        }

        .breathe {
            animation: breathe 3s ease-in-out infinite;
        }

        .fade-in {
            animation: fadeIn 0.7s ease-out forwards;
        }

        .fade-in-scale {
            animation: fadeInScale 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        .fade-in-up {
            animation: fadeInUp 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        .fade-in-down {
            animation: fadeInDown 0.7s ease-out forwards;
        }

        .fade-in-blur {
            animation: fadeInBlur 0.9s ease-out forwards;
        }

        .stagger-fade-in {
            opacity: 0;
            animation: staggerFadeInUp 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        .float-up {
            animation: floatUp 1s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        .shimmer-bg {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.1) 50%, rgba(255, 255, 255, 0) 100%);
            background-size: 1000px 100%;
            animation: shimmer 3s infinite;
        }

        .glow-pulse-anim {
            animation: glow-pulse 3s ease-in-out infinite;
        }

        .smooth-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Stagger delay utilities */
        .delay-1 {
            animation-delay: 0.1s;
        }

        .delay-2 {
            animation-delay: 0.2s;
        }

        .delay-3 {
            animation-delay: 0.3s;
        }

        .delay-4 {
            animation-delay: 0.4s;
        }

        .delay-5 {
            animation-delay: 0.5s;
        }

        .delay-6 {
            animation-delay: 0.6s;
        }

        .delay-7 {
            animation-delay: 0.7s;
        }

        .delay-8 {
            animation-delay: 0.8s;
        }

        /* Intersection observer animations for scroll effects */
        .scroll-fade {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.9s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .scroll-fade.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .scroll-fade-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: all 0.9s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .scroll-fade-left.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .scroll-fade-right {
            opacity: 0;
            transform: translateX(50px);
            transition: all 0.9s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .scroll-fade-right.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .scroll-fade-scale {
            opacity: 0;
            transform: scale(0.88);
            transition: all 0.9s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .scroll-fade-scale.visible {
            opacity: 1;
            transform: scale(1);
        }

        .scroll-fade-blur {
            opacity: 0;
            filter: blur(15px);
            transition: all 0.9s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .scroll-fade-blur.visible {
            opacity: 1;
            filter: blur(0);
        }

        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: linear-gradient(180deg, #0f172a 0%, #1a1f3a 100%);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #0ea5e9 0%, #06b6d4 100%);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #06b6d4 0%, #14b8a6 100%);
        }

        .glass-effect {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(14, 165, 233, 0.2);
            border-radius: 1rem;
        }

        .swiper {
            overflow: visible;
        }

        .swiper-pagination-bullet {
            background: rgba(14, 165, 233, 0.4);
            opacity: 1;
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .swiper-pagination-bullet-active {
            background: #0ea5e9;
            box-shadow: 0 0 10px rgba(14, 165, 233, 0.5);
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: #0ea5e9;
            background: rgba(14, 165, 233, 0.1);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 1.5px solid #0ea5e9;
            position: absolute;
            top: 50%;
            z-index: 10;
        }

        .swiper-button-next {
            right: 0;
        }

        .swiper-button-prev {
            left: 0;
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            background: rgba(14, 165, 233, 0.25);
            transform: scale(1.15);
            cursor: pointer;
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 16px;
            font-weight: bold;
        }

        .carousel-container {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            padding: 0 60px;
        }

        .carousel-container .swiper {
            width: 100%;
            max-width: 100%;
        }

        @media (max-width: 768px) {
            .carousel-container {
                padding: 0 50px;
            }

            .swiper-button-next,
            .swiper-button-prev {
                width: 36px;
                height: 36px;
            }

            .swiper-button-next::after,
            .swiper-button-prev::after {
                font-size: 14px;
            }
        }

        @media (max-width: 640px) {
            .carousel-container {
                padding: 0 40px;
            }

            .swiper-button-next,
            .swiper-button-prev {
                width: 32px;
                height: 32px;
            }

            .swiper-button-next::after,
            .swiper-button-prev::after {
                font-size: 12px;
            }
        }

        /* Enhanced Form Input Styles */
        input[type="text"],
        input[type="email"],
        textarea,
        select {
            -webkit-appearance: none;
            appearance: none;
            background-color: rgba(71, 85, 105, 0.5) !important;
            caret-color: #0ea5e9;
            border-radius: 0.625rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        textarea:focus,
        select:focus {
            background-color: rgba(71, 85, 105, 0.8) !important;
            border-color: #0ea5e9 !important;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.25), inset 0 0 8px rgba(14, 165, 233, 0.1) !important;
            outline: none;
        }

        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%230ea5e9' d='M8 11L2 5h12z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.875rem center;
            background-size: 1.2em 1.2em;
            padding-right: 2.75rem;
            text-align: center;
        }

        select option {
            background-color: #1e293b;
            color: #e2e8f0;
            padding: 0.75rem;
        }

        input[type="checkbox"] {
            cursor: pointer;
            accent-color: #0ea5e9;
            border-radius: 0.375rem;
            width: 1.25rem;
            height: 1.25rem;
        }

        input[type="radio"] {
            cursor: pointer;
            accent-color: #fbbf24;
            width: 1.5rem;
            height: 1.5rem;
        }

        /* Remove default input styling on mobile */
        @media (max-width: 768px) {

            input[type="text"],
            input[type="email"],
            textarea,
            select {
                font-size: 16px;
                padding: 12px 16px;
                border-radius: 0.625rem;
            }
        }

        .card-glow {
            position: relative;
            overflow: hidden;
            border-radius: 1.25rem;
        }

        .card-glow::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 50%;
        }

        .card-glow:hover::before {
            opacity: 1;
        }

        section {
            position: relative;
            overflow: hidden;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            opacity: 0.08;
            filter: blur(40px);
            animation: float 8s ease-in-out infinite;
        }

        .blob-1 {
            width: 400px;
            height: 400px;
            background: #0ea5e9;
            top: -100px;
            right: -50px;
        }

        .blob-2 {
            width: 300px;
            height: 300px;
            background: #06b6d4;
            bottom: -80px;
            left: -50px;
            animation-direction: reverse;
        }

        .blob-3 {
            width: 250px;
            height: 250px;
            background: #0ea5e9;
            top: 40%;
            right: 5%;
            animation-delay: 2s;
        }

        .gradient-text {
            background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 50%, #14b8a6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .gradient-animate {
            background-size: 200% 200%;
            animation: gradient-shift 3s ease infinite;
        }

        @keyframes gradient-shift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .image-overlay::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.1) 0%, rgba(6, 182, 212, 0.05) 100%);
            border-radius: inherit;
        }

        .stat-card {
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.08) 0%, rgba(6, 182, 212, 0.03) 100%);
            border-radius: 0.875rem;
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
            border-radius: 0.875rem;
            margin: 0 auto 1rem;
        }

        @media (max-width: 768px) {
            .blob {
                opacity: 0.04;
            }

            .swiper-button-next {
                right: auto;
                left: 50%;
                transform: translateX(-30px) translateY(-50%);
            }

            .swiper-button-prev {
                left: 50%;
                transform: translateX(-100px) translateY(-50%);
            }
        }

    </style>


    <title>{{ __('Atannex - Breaking News from Lebialem Division, Cameroon') }}</title>

</head>
<body>

    @include('landing.sections.navbar')

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Close mobile menu when a link is clicked
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });
        });

        // ===== PROFESSIONAL SCROLL ANIMATIONS =====
        const observerOptions = {
            threshold: 0.1
            , rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    // Add animation class with staggered delay
                    entry.target.style.animationDelay = `${index * 0.1}s`;
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Apply scroll animations to sections on page load
        window.addEventListener('load', () => {
            // Testimonial cards
            document.querySelectorAll('#reviews .card-glow').forEach(el => {
                el.classList.add('scroll-fade');
                observer.observe(el);
            });

            // Partner cards
            document.querySelectorAll('#partners .flex.items-center').forEach(el => {
                el.classList.add('scroll-fade-scale');
                observer.observe(el);
            });

            // Media partner cards
            document.querySelectorAll('#leaders .flex.items-center').forEach(el => {
                el.classList.add('scroll-fade-scale');
                observer.observe(el);
            });

            // Gallery items
            document.querySelectorAll('#gallery [class*="aspect-square"]').forEach(el => {
                el.classList.add('scroll-fade');
                observer.observe(el);
            });

            // Feature cards
            document.querySelectorAll('#features .card-glow').forEach(el => {
                el.classList.add('scroll-fade');
                observer.observe(el);
            });

            // Category cards
            document.querySelectorAll('#categories .group').forEach(el => {
                el.classList.add('scroll-fade');
                observer.observe(el);
            });

            // Team member cards
            document.querySelectorAll('#team .group').forEach(el => {
                el.classList.add('scroll-fade');
                observer.observe(el);
            });

            // Mission and Vision cards
            document.querySelectorAll('#about .card-glow').forEach((el, idx) => {
                if (idx === 0) {
                    el.classList.add('scroll-fade-left');
                } else {
                    el.classList.add('scroll-fade-right');
                }
                observer.observe(el);
            });
        });

        // Smooth reveal animation for hero section on page load
        window.addEventListener('load', () => {
            const heroSection = document.getElementById('hero');
            if (heroSection) {
                heroSection.style.animation = 'fadeIn 1s ease-out';
            }
        });

        // Add staggered animations to list items and grid items
        document.addEventListener('DOMContentLoaded', () => {
            // Stagger animations for stat cards
            document.querySelectorAll('.stat-card').forEach((card, idx) => {
                card.classList.add('fade-in-scale');
                card.style.animationDelay = `${0.4 + idx * 0.15}s`;
            });
        });

    </script>

    @include('landing.sections.hero')

    @include('landing.sections.about')

    @include('landing.sections.testimonial')

    @include('landing.sections.partner')

    @include('landing.sections.industry')

    @include('landing.sections.gallery')

    @include('landing.sections.feature')

    @include('landing.sections.category')

    @include('landing.sections.team')

    @include('landing.partials.footer')

    <script>
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) target.scrollIntoView({
                    behavior: 'smooth'
                    , block: 'start'
                });
            });
        });

    </script>
</body>
</html>
