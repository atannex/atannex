<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Protected Routes
| Middleware: Onboarded (auth + verified + password.confirm)
|--------------------------------------------------------------------------
*/

Route::middleware(['onboarded'])->group(function () {

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
Route::middleware(['pages'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Social Media Sharing Routes (PREFIXED TO PREVENT COLLISION)
    |--------------------------------------------------------------------------
    */
    Route::prefix('share')->group(function () {
        Route::get('{platform:platform}/{post:slug}', [ShareController::class, 'share'])
            ->name('share');
    });

    /*
    |--------------------------------------------------------------------------
    | Region Page Catch-All (SAFE, DOES NOT MATCH OTHER ROUTES)
    |--------------------------------------------------------------------------
    */
    Route::controller(RegionController::class)->group(function () {
        Route::get('{slug}', 'resolve')
            ->where('slug', '^(?!share|subscription|user).*$')
            ->name('page.index');
    });
});
