<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::middleware('user.logs')
    ->controller(HomeController::class)->group(function () {
        Route::get('/', 'index')->name('home');
        Route::get('/about-us', 'about')->name('about');
        Route::get('/contact-us', 'contact')->name('contact');
        Route::get('/gallery', 'gallery')->name('gallery');
    });
