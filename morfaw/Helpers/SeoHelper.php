<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

if (! function_exists('seo_title')) {

    /**
     * Generate a newsroom-style SEO title.
     * Example:
     * Lebialem: Top stories, breaking news & headlines – Atannex
     */
    function seo_title(
        ?string $context = null,
        ?string $descriptor = null,
        int $descriptorMaxLength = 60
    ): string {

        $defaultDescriptor = __('Top stories, breaking news & headlines');

        $context ??= Auth::check()
            ? Auth::user()->name
            : null;

        $descriptor = $descriptor
            ? Str::limit(sentence_case($descriptor), $descriptorMaxLength, '...')
            : sentence_case($defaultDescriptor);

        $brand = config('app.name');

        $title = [];

        if ($context) {
            $title[] = sentence_case($context) . ':';
        }

        $title[] = $descriptor;

        return implode(' ', $title) . ' – ' . $brand;
    }
}

/**
 * BBC / CNN style sentence case.
 */
if (! function_exists('sentence_case')) {
    function sentence_case(string $value): string
    {
        $value = trim($value);

        if ($value === '') {
            return $value;
        }

        $lower = mb_strtolower($value);

        return mb_strtoupper(mb_substr($lower, 0, 1)) . mb_substr($lower, 1);
    }
}
