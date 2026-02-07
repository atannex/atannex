<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\RegionController;

/*
|--------------------------------------------------------------------------
| Static / Core Pages
|--------------------------------------------------------------------------
| Always define exact routes first
*/
Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/about-atannex', 'about')->name('about');
    Route::get('/contact-atannex', 'contact')->name('contact');
});

/*
|--------------------------------------------------------------------------
| Social Media Sharing (External Access)
|--------------------------------------------------------------------------
| Prefixed to avoid slug conflicts
*/
Route::prefix('share')->group(function () {
    Route::get('{platform:platform}/{post:slug}', [ShareController::class, 'share'])
        ->name('share');
});

/*
|--------------------------------------------------------------------------
| CMS / Region Catch-All (MUST BE LAST)
|--------------------------------------------------------------------------
*/
Route::controller(RegionController::class)->group(function () {
    Route::get('{slug}', 'resolve')
        ->name('page.index');
});
