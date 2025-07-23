<?php

return [

    'types' => [
        'privacy',
        'terms',
        'faq',
        'guidelines',
        'testimonials',
        'help-center',
    ],

    'slugs' => [

        /*
        |--------------------------------------------------------------------------
        | Laravel Authentication Routes
        |--------------------------------------------------------------------------
        | Reserved to avoid conflicts with Laravel's built-in auth routes.
        | Includes all top-level route segments used by Auth::routes().
        */
        'login',            // /login
        'logout',           // /logout
        'register',         // /register
        'password',         // /password/reset, /password/email, etc.
        'email',            // /email/verify, /email/resend
        'verify',           // Optional alias used in verification workflows
        'auth',             // Reserved for potential auth grouping
        'user',             // Commonly used for user profiles or settings

        /*
        |--------------------------------------------------------------------------
        | Public Static Pages
        |--------------------------------------------------------------------------
        | Routes defined in HomeController for publicly accessible static pages.
        */
        'home',             // /
        'about-us',         // /about-us
        'contact-us',       // /contact-us
        'ceo-atannex',      // /ceo-atannex
        'faqs',             // /faqs
        'gallery',          // /gallery
        'testimonials',     // /testimonials
    ],

];
