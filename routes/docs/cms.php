<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'password.confirm'])
    ->controller(PageController::class)
    ->group(function () {
        $reservedSlugs = implode('|', config('reserved.slugs'));

        Route::get('{slug}', PageController::class)
            ->name('page.index')
            ->where('slug', "^(?!$reservedSlugs)([\\w\\/-]+)$");
    });
