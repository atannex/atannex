<?php

use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

$typePattern = implode('|', config('reserved.types'));

Route::prefix('documents')
    ->middleware(['throttle:60,1', 'api'])
    ->name('document.')
    ->group(function () use ($typePattern) {
        Route::apiResource('/', DocumentController::class)
            ->parameters(['' => 'document'])
            ->except(['show']);

        Route::get('{type}', [DocumentController::class, 'index'])
            ->name('index')
            ->where('type', "^($typePattern)$");

        Route::get('{type}/{slug}', [DocumentController::class, 'show'])
            ->name('show')
            ->where('type', "^($typePattern)$");
    });
