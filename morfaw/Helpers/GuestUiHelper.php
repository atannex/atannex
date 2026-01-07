<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

if (!function_exists('displayGuestData')) {
    /**
     * Build navigation data for guest-facing views.
     *
     * Returns an associative array containing the current route name, an optional home route
     * URL derived from the first main region's slug, and localized help and policy navigation items.
     *
     * @param Collection $mainRegions Collection of main region models; the first item's `slug` is used to compute `homeRoute` when present.
     * @return array{
     *     currentRoute: string|null,
     *     homeRoute: string|null,
     *     helpItems: array{ 'help-center': string, 'guidelines': string },
     *     policyItems: array{ 'privacy': string, 'terms': string }
     * }
     */
    function displayGuestData(Collection $mainRegions): array
    {
        $homeRoute = null;

        if ($mainRegions->isNotEmpty()) {
            $mainRegion = $mainRegions->first();
            $homeRoute = route('page.index', ['slug' => $mainRegion->slug]);
        }

        return [
            'currentRoute' => Route::currentRouteName(),

            'homeRoute'    => $homeRoute,

            'helpItems'    => [
                'help-center' => __('navigation.help_center'),
                'guidelines'  => __('navigation.guidelines'),
            ],
            'policyItems'  => [
                'privacy' => __('navigation.privacy_policy'),
                'terms'   => __('navigation.terms_conditions'),
            ],
        ];
    }
}