<?php

use App\Http\Controllers\RegionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Protected Routes
| Middleware: Onboarded (auth + verified + password.confirm)
|--------------------------------------------------------------------------
*/

Route::middleware(['onboarded'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Subscription Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/subscription/confirm/{token}', [SubscriptionController::class, 'confirm'])
        ->name('subscription.confirm');

    /*
    |--------------------------------------------------------------------------
    | User Name Completion Routes
    | Prefix: /user/name
    |--------------------------------------------------------------------------
    */
    Route::prefix('user/name')
        ->controller(ProfileController::class)
        ->group(function () {
            Route::get('set/{token}', 'show')->name('name.index');
            Route::post('complete/{token}', 'store')->name('name.complete');
        });
});

/*
|--------------------------------------------------------------------------
| Page Routes
| Middleware: Pages (onboarded + complete.name)
|--------------------------------------------------------------------------
*/
Route::middleware(['pages'])
    ->controller(RegionController::class)
    ->group(function () {
        Route::get('{slug}', 'resolve')
            ->where('slug', '.*')
            ->name('page.index');
    });
