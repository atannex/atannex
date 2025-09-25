<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;

Route::middleware('user.logs')
    ->name('document.')
    ->group(function () {
        Route::get('/how-to-use-atannex/{type}', [DocumentController::class, 'index'])
            ->name('index');

        Route::get('/how-to-use-atannex/{type}/{slug}', [DocumentController::class, 'show'])
            ->name('show');
    });
