<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SubscriptionController;

Route::get('/subscription/confirm/{token}', [SubscriptionController::class, 'confirm'])
    ->name('subscription.confirm');

Route::middleware(['auth', 'verified', 'password.confirm', 'user.logs'])
    ->controller(PageController::class)
    ->group(function () {
        Route::get('{slug}', 'resolve')
            ->where('slug', '.*')
            ->name('page.index');
    });

