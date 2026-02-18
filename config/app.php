<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Platform Identity
    |--------------------------------------------------------------------------
    | Core branding and SEO-facing metadata.
    | These values are frequently reused across views, meta tags, and feeds.
    */

    'name' => env('APP_NAME', 'Atannex'),
    'title' => env('APP_TITLE', 'Atannex: Lebialem Community News'),
    'image' => env('APP_IMAGE', '/logo.png'),
    'description' => env(
        'APP_DESCRIPTION',
        'Daily updates, headlines, and reports from Lebialem and beyond.'
    ),

    /*
    |--------------------------------------------------------------------------
    | Editorial & Contact Information
    |--------------------------------------------------------------------------
    | Centralized official contacts, structured by responsibility.
    | Keeps mail routing consistent across the platform.
    */

    'contacts' => [

        // System-level notifications (errors, alerts, cron)
        'notification' => env(
            'APP_NOTIFICATION_EMAIL',
            'notification@atannex.com'
        ),

        // Public-facing and internal departments
        'email' => [
            'editorial' => env('APP_EDITORIAL_EMAIL', 'editorial@atannex.com'),
            'newsroom' => env('APP_NEWSROOM_EMAIL', 'newsroom@atannex.com'),
            'support' => env('APP_SUPPORT_EMAIL', 'support@atannex.com'),
            'info' => env('APP_INFO_EMAIL', 'info@atannex.com'),
            'noreply' => env('APP_NOREPLY_EMAIL', 'noreply@atannex.com'),
            'admin' => env('APP_ADMIN_EMAIL', 'admin@atannex.com'),
            'press' => env('APP_PRESS_EMAIL', 'press@atannex.com'),
            'advertising' => env('APP_ADVERTISING_EMAIL', 'ads@atannex.com'),
            'tips' => env('APP_TIPS_EMAIL', 'tips@atannex.com'),
            'abuse' => env('APP_ABUSE_EMAIL', 'abuse@atannex.com'),
            'privacy' => env('APP_PRIVACY_EMAIL', 'privacy@atannex.com'),
            'legal' => env('APP_LEGAL_EMAIL', 'legal@atannex.com'),
            'security' => env('APP_SECURITY_EMAIL', 'security@atannex.com'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Environment & Debug
    |--------------------------------------------------------------------------
    | Debug should NEVER be enabled in production.
    */

    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | URL & Timezone
    |--------------------------------------------------------------------------
    | Always store timestamps in UTC.
    | Convert to user/guest timezone at render time.
    */

    'url' => env('APP_URL', 'https://atannex.com'),
    'timezone' => env('APP_TIMEZONE', 'UTC'),

    /*
    |--------------------------------------------------------------------------
    | Localization
    |--------------------------------------------------------------------------
    */

    'locale' => env('APP_LOCALE', 'en'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Encryption
    |--------------------------------------------------------------------------
    */

    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',

    // Allows seamless key rotation
    'previous_keys' => array_filter(
        explode(',', env('APP_PREVIOUS_KEYS', ''))
    ),

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode
    |--------------------------------------------------------------------------
    | Database driver is recommended for clustered deployments.
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];
