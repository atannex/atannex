<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShareController;
use Illuminate\Support\Facades\Route;

Route::middleware('track.activity')
    ->controller(HomeController::class)->group(function () {
        Route::get('/', 'index')->name('home');
        Route::get('/about-us', 'about')->name('about');
        Route::get('/contact-us', 'contact')->name('contact');
        Route::get('/gallery', 'gallery')->name('gallery');
    });

/*
    |--------------------------------------------------------------------------
    | Social Media Sharing Routes
    |--------------------------------------------------------------------------
    */

Route::get('/share/{platform:platform}/{post:slug}', [ShareController::class, 'share'])->name('share');
