<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\ShareController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| FALLBACK (PRODUCTION SAFE)
|--------------------------------------------------------------------------
| Never redirect back (can loop or leak sensitive referrer chains)
*/

Route::fallback(function () {
    return redirect()
        ->back()
        ->with('error', 'Page not found.');
});

/*
|--------------------------------------------------------------------------
| SHARE ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('/share')->group(function () {
    Route::get('/{platform}/{post}', [ShareController::class, 'share'])
        ->name('share');
});


/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Auth::routes(['verify' => true]);

/*
|--------------------------------------------------------------------------
| STATIC PAGES
|--------------------------------------------------------------------------
*/
Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/about-us', 'about')->name('about');
    Route::get('/contact-us', 'contact')->name('contact');
});

/*
|--------------------------------------------------------------------------
| CMS / CONTENT ROUTES (ORDER MATTERS)
|--------------------------------------------------------------------------
| IMPORTANT:
| More specific routes must come BEFORE generic catch-all routes.
*/
Route::controller(RegionController::class)->group(function () {

    /*
|--------------------------------------------------------------------------
| ARCHIVES (STRICT STRUCTURE)
|--------------------------------------------------------------------------
*/

    Route::get('/archive/{year}', 'archiveYear')
        ->whereNumber('year')
        ->name('archive.year');

    Route::get('/archive/{year}/{month}', 'archiveMonth')
        ->whereNumber('year')
        ->whereNumber('month')
        ->name('archive.month');

    /*
    |--------------------------------------------------------------------------
    | CONTENT TAXONOMY ROUTES
    |--------------------------------------------------------------------------
    | Use wildcard ONLY where necessary
    */

    Route::get('/documents/{type}', 'documentListing')
        ->where('type', '.*')
        ->name('documents.list');

    Route::get('/document/{slug}', 'singleDocument')
        ->where('slug', '.*')
        ->name('documents.show');

    Route::get('/posts/{slug}', 'post')
        ->where('slug', '.*')
        ->name('posts.show');

    Route::get('/authors/{slug}', 'author')
        ->where('slug', '.*')
        ->name('authors.show');

    Route::get('/tags/{slug}', 'tag')
        ->where('slug', '.*')
        ->name('tags.show');

    /*
    |--------------------------------------------------------------------------
    | CATEGORY / REGION SYSTEM (FIXED FOR HIERARCHICAL SLUGS)
    |--------------------------------------------------------------------------
    | These must match DB stored paths like:
    | community/village-events
    */

    Route::get('/category/{path}', 'category')
        ->where('path', '.*')
        ->name('categories.show');

    Route::get('/regions/{path}', 'region')
        ->where('path', '.*')
        ->name('regions.show');

    /*
    |--------------------------------------------------------------------------
    | CMS PAGES (LOWEST PRIORITY)
    |--------------------------------------------------------------------------
    */

    Route::get('/pages/{path}', 'page')
        ->where('path', '.*')
        ->name('pages.show');
});
