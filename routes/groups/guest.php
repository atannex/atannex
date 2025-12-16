<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShareController;


/*
|--------------------------------------------------------------------------
| Social Media Sharing Routes
|--------------------------------------------------------------------------
| Prefixed so they never collide with region, category, or post slugs.
*/

Route::prefix('share/')->group(function () {
    Route::get('{platform:platform}/{post:slug}', [ShareController::class, 'share'])
        ->name('share');
});

/*
|--------------------------------------------------------------------------
| Static Pages (HomeController)
|--------------------------------------------------------------------------
*/

Route::middleware('track.activity')
    ->controller(HomeController::class)
    ->group(function () {
        Route::get('/', 'index')->name('home');
        Route::get('about-us', 'about')->name('about');
        Route::get('contact-us', 'contact')->name('contact');
        Route::get('gallery', 'gallery')->name('gallery');
    });
