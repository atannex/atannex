<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;

$typePattern = implode('|', config('reserved.types'));

Route::name('document.')
    ->group(function () use ($typePattern) {
        Route::get('{type}', [DocumentController::class, 'index'])
            ->where('type', "^($typePattern)$")
            ->name('index');

        Route::get('{type}/{slug}', [DocumentController::class, 'show'])
            ->where('type', "^($typePattern)$")
            ->name('show');
    });
