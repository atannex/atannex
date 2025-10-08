<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

if (!function_exists('seo_title')) {
    /**
     * Generate an SEO-friendly title for pages.
     *
     * @param string|null $subject The main subject of the page.
     * @param string|null $suffix Optional suffix for the title.
     * @param int $suffixMaxLength Max length of the suffix, default 60.
     * @return string The formatted SEO title.
     */
    function seo_title(?string $subject = null, ?string $suffix = null, int $suffixMaxLength = 60): string
    {
        $defaultSuffix = __('Top Stories, Breaking News & Headlines');
        $suffix = $suffix ? Str::limit($suffix, $suffixMaxLength, '...') : $defaultSuffix;

        $subject = $subject ?? (Auth::check()
            ? Str::title(strtolower(Auth::user()->name))
            : $defaultSuffix
        );

        $appName = config('app.name');

        $parts = [$subject];

        if (strtolower(trim($suffix)) !== strtolower(trim($subject))) {
            $parts[] = $suffix;
        }
        $parts[] = $appName;

        $titleParts = array_map(function ($part) use ($subject, $appName) {
            if ($part === $appName) {
                return $appName;
            }

            return $part === $subject ? Str::title(strtolower($part)) : Str::upper($part);
        }, $parts);

        return implode(' | ', $titleParts);
    }
}

if (!function_exists('get_posts_from_tabs')) {
    /**
     * Retrieves posts from an array of tabs.
     *
     * @param array $tabs Array of tab configurations containing content or entities.
     * @return \Illuminate\Support\Collection Collection of posts.
     */
    function get_posts_from_tabs(array $tabs): Collection
    {
        return collect($tabs)->flatMap(function ($tab) {
            if (!empty($tab['content'])) {
                return $tab['content'];
            }

            return collect($tab['entities'])->flatMap(fn($region) => $region['posts']);
        });
    }
}
