<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/about-us', 'about')->name('about');
    Route::get('/ceo-atannex', 'ceo')->name('ceo');
    Route::get('/contact-us', 'contact')->name('contact');
    Route::get('/faqs', 'faq')->name('faq');
    Route::get('/gallery', 'gallery')->name('gallery');
    Route::get('/testimonials', 'testimonials')->name('testimonials');
});
