<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SubscriptionController;

Route::get('/subscription/confirm/{token}', [SubscriptionController::class, 'confirm'])
    ->name('subscription.confirm');

Route::middleware(['auth', 'verified', 'password.confirm'])
    ->controller(PageController::class)
    ->group(function () {

        Route::get('/{year}-{month}-{day}/{category}/{slug}', 'show')
            ->where([
                'year' => '\d{4}',
                'month' => '\d{2}',
                'day' => '\d{2}',
                'category' => '[A-Za-z0-9\-_]+',
                'slug' => '[A-Za-z0-9\-_]+',
            ])
            ->name('post.show');

        Route::get('/{slug}', 'resolve')
            ->where('slug', '[A-Za-z0-9\-_\/]+')
            ->name('page.index');
    });
