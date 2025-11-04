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
        return collect($tabs)
            ->flatMap(function ($tab) {
                if (!empty($tab['content'])) {
                    return $tab['content'];
                }

                return collect($tab['entities'])
                    ->flatMap(fn($region) => $region['posts']);
            })
            ->unique('id')
            ->values();
    }
}

if (!function_exists('format_count')) {
    /**
     * Format large numbers into short form: 1.1k, 2.5M, 3B, 4T.
     *
     * @param int|float|string $number
     * @param int $decimals               Number of decimals for abbreviated values (default: 1)
     * @param bool $trimTrailingZeros     Remove trailing .0 (e.g., 1.0k -> 1k)
     * @return string
     */
    function format_count($number, int $decimals = 1, bool $trimTrailingZeros = true): string
    {
        if (!is_numeric($number)) {
            $number = 0;
        }
        $num = (float) $number;
        $sign = $num < 0 ? '-' : '';
        $n = abs($num);

        if ($n < 1000) {
            return $sign . number_format((int) $n);
        }

        $units = [
            12 => 'T',
            9  => 'B',
            6  => 'M',
            3  => 'k',
        ];

        foreach ($units as $power => $suffix) {
            $threshold = 10 ** $power;
            if ($n >= $threshold) {

                $scaled = $n / $threshold;

                $rounded = round($scaled, $decimals);

                if ($rounded >= 1000 && $power < 12) {
                    $nextPower = $power + 3;
                    $nextScaled = $n / (10 ** $nextPower);
                    $rounded = round($nextScaled, $decimals);
                    $suffix = $units[$nextPower] ?? $suffix;
                }

                $numeric = number_format($rounded, $decimals, '.', '');
                if ($trimTrailingZeros && $decimals > 0) {
                    $numeric = rtrim(rtrim($numeric, '0'), '.');
                }

                return $sign . $numeric . $suffix;
            }
        }

        return $sign . number_format((int) $n);
    }
}
