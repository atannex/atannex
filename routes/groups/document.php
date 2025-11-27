<?php

use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

Route::middleware('track.activity')
    ->name('document.')
    ->group(function () {
        Route::get('/how-to-use-atannex/{type}', [DocumentController::class, 'index'])
            ->name('index');

        Route::get('/how-to-use-atannex/{type}/{slug}', [DocumentController::class, 'show'])
            ->name('show');
    });
