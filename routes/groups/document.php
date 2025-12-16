<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;

/*
|--------------------------------------------------------------------------
| Public Documents (Tracked, No Auth)
|--------------------------------------------------------------------------
| These routes must be registered BEFORE the CMS catch-all
|--------------------------------------------------------------------------
*/

Route::prefix('how-to-use-atannex')
    ->middleware('track.activity')
    ->name('document.')
    ->controller(DocumentController::class)
    ->group(function () {

        Route::get('{type}', 'index')
            ->where('type', '[a-zA-Z0-9\-]+')
            ->name('index');

        Route::get('{type}/{slug}', 'show')
            ->where([
                'type' => '[a-zA-Z0-9\-]+',
                'slug' => '[a-zA-Z0-9\-]+',
            ])
            ->name('show');
    });
