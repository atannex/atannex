<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\RegionController;

// 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

/*
|--------------------------------------------------------------------------
| Static / Core Pages
|--------------------------------------------------------------------------
| Always define exact routes first
*/

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/about-US', 'about')->name('about');
    Route::get('/contact-US', 'contact')->name('contact');
});

/*
|--------------------------------------------------------------------------
| Social Media Sharing (External Access)
|--------------------------------------------------------------------------
| Prefixed to avoid slug conflicts
*/
Route::prefix('/share')->group(function () {
    Route::get('{platform:platform}/{post:slug}', [ShareController::class, 'share'])
        ->name('share');
});

/*
|--------------------------------------------------------------------------
| CMS / Region Catch-All (MUST BE LAST)
|--------------------------------------------------------------------------
*/
Route::controller(RegionController::class)->group(function () {
    Route::get('/{slug}', 'resolve')
        ->where('slug', '^(?!login|register|password|email|logout).*$')
        ->name('page.index');
});

Auth::routes(['verify' => true]);
// ->middleware(['auth', 'verified', 'password.confirm'])
