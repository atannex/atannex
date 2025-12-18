<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

if (! function_exists('seo_title')) {
    /**
     * Generate an SEO-friendly title.
     */
    function seo_title(
        ?string $subject = null,
        ?string $suffix = null,
        int $suffixMaxLength = 60
    ): string {
        $defaultSuffix = __('Top Stories, Breaking News & Headlines');
        $suffix = $suffix
            ? Str::limit($suffix, $suffixMaxLength, '...')
            : $defaultSuffix;

        $subject ??= Auth::check()
            ? Str::title(strtolower(Auth::user()->name))
            : $defaultSuffix;

        $appName = config('app.name');

        $parts = [$subject];

        if (strcasecmp(trim($suffix), trim($subject)) !== 0) {
            $parts[] = $suffix;
        }

        $parts[] = $appName;

        return collect($parts)
            ->map(function ($part) use ($subject, $appName) {
                if ($part === $appName) {
                    return $appName;
                }

                return $part === $subject
                    ? Str::title(strtolower($part))
                    : Str::upper($part);
            })
            ->implode(' | ');
    }
}
