<?php

use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

Route::prefix('using-the-atannex')
    ->name('document.')
    ->controller(DocumentController::class)
    ->group(function () {
        Route::get('{type}', 'index')
            ->name('index')
            ->where('type', '^(privacy|terms|faq|guidelines|testimonials|help-center)$');

        Route::get('{type}/{slug}', 'show')
            ->name('show')
            ->where('type', '^(privacy|terms|faq|guidelines|testimonials|help-center)$');
    });
