<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

if (! function_exists('displayGuestData')) {
    function displayGuestData(array $global): array
    {
        $mainRegion = $global['mainRegions']->first();

        return [
            'currentRoute' => Route::currentRouteName(),

            'homeRoute' => Auth::guest() || ! $mainRegion
                ? route('home')
                : route('page.index', ['slug' => $mainRegion->slug]),

            'helpItems' => [
                'help-center' => __('Help Center'),
                'guidelines'  => __('Guidelines'),
            ],

            'policyItems' => [
                'privacy' => __('Privacy Policy'),
                'terms'   => __('Terms & Conditions'),
            ],
        ];
    }
}
