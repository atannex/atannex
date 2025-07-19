<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Public Routes (Static Pages)
|--------------------------------------------------------------------------
| These are public-facing, non-auth routes for the landing pages.
*/

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/about-us', 'about')->name('about');
    Route::get('/ceo-atannex', 'ceo')->name('ceo');
    Route::get('/contact-us', 'contact')->name('contact');
    Route::get('/faqs', 'faq')->name('faq');
    Route::get('/gallery', 'gallery')->name('gallery');
    Route::get('/testimonials', 'testimonials')->name('testimonials');
});

/*
|--------------------------------------------------------------------------
| Document Routes (Policy, FAQ, Help Center, etc.)
|--------------------------------------------------------------------------
| Prefix: /using-the-atannex
| Matches types: privacy, terms, faq, guidelines, testimonials, help-center
*/
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

/*
|--------------------------------------------------------------------------
| Auth Routes (Laravel built-in)
|--------------------------------------------------------------------------
*/
Auth::routes(['verify' => true]);

/*
|--------------------------------------------------------------------------
| Dynamic CMS Pages (Authenticated, Verified)
|--------------------------------------------------------------------------
| Catches dynamic slugs for user-defined pages.
| Excludes known routes to avoid conflicts.
*/
Route::middleware(['auth', 'verified', 'password.confirm'])
    ->controller(PageController::class)
    ->group(function () {
        Route::get('{slug}', PageController::class)
            ->name('page.index')
            ->where('slug', '^(?!login|register|password|logout|using-the-atannex|contact-us|about-us|ceo-atannex|faqs|testimonials|gallery)[\w\/-]*$');
    });
