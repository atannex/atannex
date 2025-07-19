<?php

return [

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

        /*
        |--------------------------------------------------------------------------
        | Document Routes
        |--------------------------------------------------------------------------
        | Routes handled by DocumentController, grouped under /using-the-atannex.
        */
        'using-the-atannex', // Prefix for all document-related pages

        /*
        |--------------------------------------------------------------------------
        | Admin & CMS Control Panel
        |--------------------------------------------------------------------------
        | Common admin entry points that should not conflict with dynamic slugs.
        */
        'admin',            // /admin
        'dashboard',        // /dashboard
        'panel',            // /panel (for future expansion)
        'cms',              // /cms (for future CMS dashboard or API)

    ],

];
