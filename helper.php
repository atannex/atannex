<?php

use App\Enums\Icon;
use App\Models\Others\SocialMedia;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

if (! function_exists('category_display_data')) {

    function category_display_data($category): array
    {
        $root = $category->getAncestors()->last() ?? $category;
        $rootName = strtolower($root->name);

        $label = in_array($rootName, ['ruler', 'rulers'], true) && $category->parent
            ? "{$category->parent->name} → {$category->name}"
            : $category->name;

        $bgSrc = $category->posts->first()?->image
            ? asset('storage/' . $category->posts->first()->image)
            : '';

        return compact('label', 'bgSrc');
    }
}

if (! function_exists('displayGuestData')) {

    function displayGuestData(Collection $mainRegions): array
    {
        $homeRoute = null;

        if ($mainRegions->isNotEmpty()) {
            $mainRegion = $mainRegions->first();
            $homeRoute = route('regions.show', ['path' => $mainRegion->slug]);
        }

        return [
            'currentRoute' => Route::currentRouteName(),

            'homeRoute' => $homeRoute,

            'helpItems' => [
                'help-center' => __('navigation.help_center'),
                'guidelines' => __('navigation.guidelines'),
            ],
            'policyItems' => [
                'privacy' => __('navigation.privacy_policy'),
                'terms' => __('navigation.terms_conditions'),
            ],
        ];
    }
}

if (! function_exists('format_count')) {

    function format_count(int|float|string|null $number, int $decimals = 1, bool $trimZeros = true): string
    {
        if (! is_numeric($number)) {
            return '0';
        }

        $num = (float) $number;
        $sign = $num < 0 ? '-' : '';
        $value = abs($num);

        if ($value < 1000) {
            return $sign . number_format((int) $value);
        }

        $suffixes = [
            12 => 'T',
            9 => 'B',
            6 => 'M',
            3 => 'k',
        ];

        foreach ($suffixes as $exp => $suffix) {
            $threshold = 10 ** $exp;

            if ($value >= $threshold) {
                $scaled = $value / $threshold;
                $formatted = number_format($scaled, $decimals, '.', '');

                if ($trimZeros) {
                    $formatted = rtrim(rtrim($formatted, '0'), '.');
                }

                return $sign . $formatted . $suffix;
            }
        }

        return $sign . number_format((int) $value);
    }
}

if (! function_exists('get_posts_from_tabs')) {

    function get_posts_from_tabs(array $tabs): Collection
    {
        return collect($tabs)
            ->flatMap(
                fn($tab) => $tab['content']
                    ?? collect($tab['entities'])
                    ->flatMap(fn($region) => $region['posts'])
            )
            ->unique('id')
            ->values();
    }
}

if (! function_exists('map_social_media')) {

    function map_social_media(SocialMedia $media): array
    {
        $data = [];

        if (class_exists(Icon::class) && method_exists(Icon::class, 'getData')) {
            $data = Icon::getData($media->platform);
        }

        return [
            'url'   => $media->url,
            'label' => $data['label'],
            'icon'  => $data['icon'],
            'color' => $data['color'],
        ];
    }
}

if (! function_exists('seo_title')) {

    function seo_title(?string $context = null, ?string $descriptor = null, int $descriptorMaxLength = 60): string
    {

        $defaultDescriptor = __('Top stories, breaking news & headlines');
        $brand = config('app.name');

        $descriptor = $descriptor
            ? Str::limit(sentence_case($descriptor), $descriptorMaxLength, '…')
            : sentence_case($defaultDescriptor);

        $titleParts = [];

        if ($context) {
            $titleParts[] = sentence_case($context) . ':';
        }

        $titleParts[] = $descriptor;

        return implode(' ', $titleParts) . ' – ' . $brand;
    }
}

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

if (! function_exists('normalizeIds')) {

    function normalizeIds(int|string|iterable|null $ids): array
    {
        if ($ids === null || $ids === []) {
            return [];
        }

        if (is_iterable($ids)) {
            return array_values(
                is_array($ids)
                    ? $ids
                    : iterator_to_array($ids, false)
            );
        }

        return [$ids];
    }
}
