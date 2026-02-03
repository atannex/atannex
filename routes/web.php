<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\ShareController;

// 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

/*
|--------------------------------------------------------------------------
| Static / Core Pages
|--------------------------------------------------------------------------
| Always define exact routes first
*/

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/catalog', 'catalog')->name('catalog');
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
| Authentication Routes
|--------------------------------------------------------------------------
*/
Auth::routes(['verify' => true]);

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
| Middleware: auth + verified + password.confirm
*/
Route::middleware([
    'auth',
    'verified',
    'password.confirm',
])->group(function () {
    // protected routes go here
});

/*
|--------------------------------------------------------------------------
| CMS / Region Catch-All (⚠️ MUST BE LAST)
|--------------------------------------------------------------------------
| Prevents conflicts with system routes
*/
Route::controller(RegionController::class)->group(function () {

    $reserved = implode('|', config('cms.reserved_slugs'));

    Route::get('{slug}', 'resolve')
        ->where('slug', "^(?!{$reserved}).+")
        ->name('page.index');
});
