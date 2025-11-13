<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\ShareController;
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
    | Social Media Sharing Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/share/{platform:platform}/{post:slug}', [ShareController::class, 'share'])->name('share');

    /*
    |--------------------------------------------------------------------------
    | Subscription Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/subscription/verify/{token}', [SubscriptionController::class, 'verify'])->name('subscription.verify');

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
        Route::get('{slug}', 'resolve')->where('slug', '.*')->name('page.index');
    });
