<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SubscriptionController;

$reservedSlugs = implode('|', config('reserved.slugs'));

Route::get('/subscription/confirm/{token}', [SubscriptionController::class, 'confirm'])
    ->name('subscription.confirm');

Route::middleware(['auth', 'verified', 'password.confirm'])
    ->controller(PageController::class)
    ->group(function () use ($reservedSlugs) {
        Route::get('{slug}', PageController::class)
            ->name('page.index')
            ->where('slug', "^(?!$reservedSlugs)([\\w\\/-]+)$");
    });
