<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;

Route::name('document.')
    ->group(function () {
        Route::get('/using-the-atannex/{type}', [DocumentController::class, 'index'])
            ->name('index');

        Route::get('/using-the-atannex/{type}/{slug}', [DocumentController::class, 'show'])
            ->name('show');
    });
