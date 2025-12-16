<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CMS Reserved Slugs
    |--------------------------------------------------------------------------
    |
    | These slugs must NEVER be captured by the CMS catch-all route.
    | Add new entries here instead of touching route regex.
    |
    */

    'reserved_slugs' => [
        'login',
        'register',
        'logout',
        'password',
        'email',
        'verification',

        // static pages
        'about-us',
        'contact-us',
        'gallery',

        // system routes
        'subscription',
        'user',
        'dashboard',

        // document routes
        'how-to-use-atannex',
    ],

];
