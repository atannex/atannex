<?php

if (! function_exists('seo_title')) {
    /**
     * Generate an SEO-friendly page title.
     *
     * @param string      $subject The main subject (e.g., Tag name).
     * @param string|null $suffix  Optional suffix text.
     */
    function seo_title(string $subject, ?string $suffix = null): string
    {
        $defaultSuffix = __('Top Stories, Breaking News & Headlines');
        $suffix = $suffix ?? $defaultSuffix;

        return trim(sprintf('%s - %s | %s', $subject, $suffix, config('app.name')));
    }
}
