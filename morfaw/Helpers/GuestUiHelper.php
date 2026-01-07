<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

if (!function_exists('displayGuestData')) {
    /**
     * Prepare guest display data including routes and navigation items.
     *
     * @param Collection $mainRegions
     * @return array
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