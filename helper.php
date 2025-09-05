<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

if (!function_exists('seo_title')) {
    function seo_title(?string $subject = null, ?string $suffix = null, int $suffixMaxLength = 60): string
    {
        $defaultSuffix = __('Top Stories, Breaking News & Headlines');
        $suffix = $suffix ? Str::limit($suffix, $suffixMaxLength, '...') : $defaultSuffix;

        $subject = $subject ?? (Auth::check()
            ? __(':name', ['name' => Str::title(strtolower(Auth::user()->name))])
            : $defaultSuffix);

        $appName = config('app.name');

        $parts = [$subject];
        if (strtolower(trim($suffix)) !== strtolower(trim($subject))) {
            $parts[] = $suffix;
        }
        $parts[] = $appName;

        $titleParts = array_map(function ($part) use ($subject, $appName) {
            if ($part === $appName) return $appName;
            return $part === $subject ? Str::title(strtolower($part)) : Str::upper($part);
        }, $parts);

        return implode(' | ', $titleParts);
    }
}
