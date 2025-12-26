<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShareController;


// 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

/*
|--------------------------------------------------------------------------
| Global / System Routes
|--------------------------------------------------------------------------
| Routes that are accessed externally via email, bots, or third-party links
| should be defined early to avoid accidental shadowing.
*/

/*
|--------------------------------------------------------------------------
| Social Media Sharing Routes
|--------------------------------------------------------------------------
| Prefixed to prevent collision with region, category, or post slugs.
*/
Route::prefix('share')->group(function () {
    Route::get('{platform:platform}/{post:slug}', [ShareController::class, 'share'])
        ->name('share');
});

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
| Public-facing routes (auth, registration, landing pages, etc.)
*/
require __DIR__ . '/groups/guest.php';

/*
|--------------------------------------------------------------------------
| CMS Routes
|--------------------------------------------------------------------------
| Admin, editor, and protected CMS functionality
*/
require __DIR__ . '/groups/cms.php';
