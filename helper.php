<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

if (!function_exists('seo_title')) {
    /**
     * Generate an SEO-optimized page title.
     *
     * @param string|null $subject The primary title content (defaults to a welcome message or fallback).
     * @param string|null $suffix Optional suffix for the title (defaults to localized tagline).
     * @param int $suffixMaxLength Maximum length for suffix (default: 60 characters)
     * @return string The formatted SEO title.
     */
    function seo_title(?string $subject = null, ?string $suffix = null, int $suffixMaxLength = 60): string
    {
        $defaultSuffix = __('Top Stories, Breaking News & Headlines');
        $suffix = $suffix ?? $defaultSuffix;

        // Only truncate suffix if it exceeds the max length
        if (Str::length($suffix) > $suffixMaxLength) {
            // Limit without cutting mid-word
            $suffix = Str::limit($suffix, $suffixMaxLength, '...');
        }

        // Determine the main subject
        $subject = $subject ?? (Auth::check()
            ? __(':name', ['name' => Str::title(strtolower(Auth::user()->name))])
            : $defaultSuffix);

        $appName = config('app.name'); // Preserve original casing

        // Remove duplication between subject and suffix
        $subjectNormalized = strtolower(trim($subject));
        $suffixNormalized  = strtolower(trim($suffix));

        $parts = [$subject];
        if ($suffixNormalized !== $subjectNormalized) {
            $parts[] = $suffix;
        }
        $parts[] = $appName;

        // Format: subject = Title Case, suffix = UPPERCASE, app name = original
        $titleParts = array_map(function ($part) use ($appName, $subject) {
            if ($part === $appName) {
                return $appName;
            } elseif ($part === $subject) {
                return Str::title(strtolower($part)); // Title Case for subject
            } else {
                return Str::upper($part); // UPPERCASE for suffix
            }
        }, $parts);

        return implode(' | ', $titleParts);
    }
}
