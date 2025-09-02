<?php

use Illuminate\Support\Facades\Auth;

if (! function_exists('seo_title')) {
    /**
     * Generate an SEO-friendly page title.
     *
     * @param string|null $subject The main subject (defaults to "Welcome ..." or fallback).
     * @param string|null $suffix  Optional suffix text.
     */
    function seo_title(?string $subject = null, ?string $suffix = null): string
    {
        $defaultSuffix = __('Top Stories, Breaking News & Headlines');
        $suffix = $suffix ?: $defaultSuffix;

        if (! $subject) {
            $subject = Auth::check()
                ? __('Welcome :name', ['name' => Auth::user()->name])
                : $defaultSuffix;
        }

        $parts = [$subject, $suffix, config('app.name')];

        $parts = array_unique(array_filter($parts));

        return implode(' | ', $parts);
    }
}
