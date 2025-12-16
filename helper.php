<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

if (! function_exists('displayData')) {
    /**
     * Generate category display data (label + background image)
     *
     * @param  \App\Models\Category  $category
     */
    function displayData($category): array
    {
        $firstPost = $category->posts->first();
        $root = $category->getAncestors()->last() ?? $category;
        $rootName = strtolower($root->name);

        $label = ($rootName === 'ruler' || $rootName === 'rulers')
            ? ($category->parent ? "{$category->parent->name} → {$category->name}" : $category->name)
            : $category->name;

        $bgSrc = $firstPost?->image
            ? asset("storage/{$firstPost->image}")
            : '';

        return compact('label', 'bgSrc');
    }
}

if (! function_exists('seo_title')) {
    /**
     * Generate an SEO-friendly title for pages.
     *
     * @param  string|null  $subject  The main subject of the page.
     * @param  string|null  $suffix  Optional suffix for the title.
     * @param  int  $suffixMaxLength  Max length of the suffix, default 60.
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

if (! function_exists('get_posts_from_tabs')) {
    /**
     * Retrieves posts from an array of tabs.
     *
     * @param  array  $tabs  Array of tab configurations containing content or entities.
     * @return \Illuminate\Support\Collection Collection of posts.
     */
    function get_posts_from_tabs(array $tabs): Collection
    {
        return collect($tabs)
            ->flatMap(fn($tab) => $tab['content'] ?? collect($tab['entities'])->flatMap(fn($region) => $region['posts']))
            ->unique('id')
            ->values();
    }
}

if (! function_exists('format_count')) {
    /**
     * Formats a large number into a human-readable short form.
     *
     * Examples:
     *   1500     → 1.5k
     *   1234567  → 1.2M
     *   999600   → 1.0M
     *   -5000    → -5k
     *   1000     → 1k (not 1.0k if trimTrailingZeros is true)
     *
     * @param  int|float|string  $number  The number to format
     * @param  int  $decimals  Number of decimal places (default: 1)
     * @param  bool  $trimZeros  Remove trailing zeros and decimal point
     * @return string Formatted number with suffix (k, M, B, T)
     */
    function format_count(
        int|float|string $number,
        int $decimals = 1,
        bool $trimZeros = true
    ): string {

        if (! is_numeric($number)) {
            return '0';
        }

        $num = (float) $number;
        $sign = $num < 0 ? '-' : '';
        $value = abs($num);

        if ($value < 1000) {
            $formatted = number_format((int) $value);

            return $sign . $formatted;
        }

        $suffixes = [
            12 => 'T',
            9 => 'B',
            6 => 'M',
            3 => 'k',
        ];

        foreach ($suffixes as $exponent => $suffix) {
            $threshold = 10 ** $exponent;

            if ($value >= $threshold) {
                $scaled = $value / $threshold;
                $formatted = number_format($scaled, $decimals, '.', '');

                if ($scaled >= 1000 && $exponent < 12) {
                    $nextExponent = $exponent + 3;
                    $nextScaled = $value / (10 ** $nextExponent);
                    $formatted = number_format($nextScaled, $decimals, '.', '');
                    $suffix = $suffixes[$nextExponent] ?? $suffix;
                }

                if ($trimZeros && $decimals > 0) {
                    $formatted = rtrim(rtrim($formatted, '0'), '.');
                    if ($formatted === '') {
                        $formatted = '0';
                    }
                }

                return $sign . $formatted . $suffix;
            }
        }

        return $sign . number_format((int) $value);
    }
}

if (! function_exists('displayGuestData')) {
    function displayGuestData(array $global): array
    {
        $mainRegion = $global['mainRegions']->first();

        return [
            'currentRoute' => Route::currentRouteName(),

            'homeRoute' => Auth::guest() || ! $mainRegion
                ? route('home')
                : route('page.index', ['slug' => $mainRegion->slug]),

            'helpItems' => [
                'help-center' => __('Help Center'),
                'guidelines'  => __('Guidelines'),
            ],

            'policyItems' => [
                'privacy' => __('Privacy Policy'),
                'terms'   => __('Terms & Conditions'),
            ],
        ];
    }
}
