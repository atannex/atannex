<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

if (! function_exists('displayGuestData')) {
    /**
     * Build guest-facing navigation and route data based on available main regions.
     *
     * @param Collection $mainRegions Collection of region objects where the first element (if present) provides the slug used for the home route.
     * @return array{
     *     currentRoute: string|null,
     *     homeRoute: string,
     *     helpItems: array{ "help-center": string, "guidelines": string },
     *     policyItems: array{ "privacy": string, "terms": string }
     * } Associative array containing the current route name, a home route URL, localized help item labels, and localized policy item labels.
     */
    function displayGuestData(Collection $mainRegions): array
    {
        $mainRegion = $mainRegions->first();

        return [
            'currentRoute' => Route::currentRouteName(),

            'homeRoute' => $mainRegion
                ? route('page.index', ['slug' => $mainRegion->slug])
                : route('page.index'),

            'helpItems' => [
                'help-center' => __('navigation.help_center'),
                'guidelines'  => __('navigation.guidelines'),
            ],

            'policyItems' => [
                'privacy' => __('navigation.privacy_policy'),
                'terms'   => __('navigation.terms_conditions'),
            ],
        ];
    }
}