<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

if (! function_exists('displayGuestData')) {
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
