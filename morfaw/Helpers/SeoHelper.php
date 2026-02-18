<?php

use Illuminate\Support\Str;

if (! function_exists('seo_title')) {

    /**
     * Generate a newsroom-style SEO title.
     *
     * Example:
     * Lebialem: Top stories, breaking news & headlines – Atannex
     */
    function seo_title(
        ?string $context = null,
        ?string $descriptor = null,
        int $descriptorMaxLength = 60
    ): string {

        $defaultDescriptor = __('Top stories, breaking news & headlines');
        $brand = config('app.name');

        $descriptor = $descriptor
            ? Str::limit(sentence_case($descriptor), $descriptorMaxLength, '…')
            : sentence_case($defaultDescriptor);

        $titleParts = [];

        if ($context) {
            $titleParts[] = sentence_case($context).':';
        }

        $titleParts[] = $descriptor;

        return implode(' ', $titleParts).' – '.$brand;
    }
}

if (! function_exists('sentence_case')) {

    /**
     * BBC / CNN style sentence case.
     */
    function sentence_case(string $value): string
    {
        $value = trim($value);

        if ($value === '') {
            return $value;
        }

        $lower = mb_strtolower($value);

        return mb_strtoupper(mb_substr($lower, 0, 1)).mb_substr($lower, 1);
    }
}
