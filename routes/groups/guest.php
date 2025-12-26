<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegionController;


/*
|--------------------------------------------------------------------------
| CMS / REGION CATCH-ALL (⚠️ MUST BE LAST)
|--------------------------------------------------------------------------
| Prevents conflict with auth & static pages
|--------------------------------------------------------------------------
*/

Route::controller(RegionController::class)->group(function () {

        $reserved = implode('|', config('cms.reserved_slugs'));

        Route::get('{slug}', 'resolve')
            ->where('slug', "^(?!{$reserved}).+")
            ->name('page.index');
    });

/*
|--------------------------------------------------------------------------
| Static Pages (HomeController)
|--------------------------------------------------------------------------
*/

Route::controller(HomeController::class)
    ->group(function () {
        Route::get('/', 'index')->name('home');
        Route::get('about-us', 'about')->name('about');
        Route::get('contact-us', 'contact')->name('contact');
    });
