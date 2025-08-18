<?php

use Illuminate\Support\Facades\Auth;

if (! function_exists('seo_title')) {
    /**
     * Generate an SEO-friendly page title.
     *
     * @param string|null $subject The main subject (defaults to "Welcome ...").
     * @param string|null $suffix  Optional suffix text.
     */
    function seo_title(?string $subject = null, ?string $suffix = null): string
    {
        if (! $subject) {
            $subject = Auth::check()
                ? 'Welcome ' . Auth::user()->name
                : 'Welcome Guest';
        }

        $defaultSuffix = __('Top Stories, Breaking News & Headlines');
        $suffix = $suffix ?? $defaultSuffix;

        return trim(sprintf('%s - %s | %s', $subject, $suffix, config('app.name')));
    }
}
