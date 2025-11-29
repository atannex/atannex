<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ShareController;

/*
    |--------------------------------------------------------------------------
    | Social Media Sharing Routes (PREFIXED TO PREVENT COLLISION)
    |--------------------------------------------------------------------------
    */

Route::prefix('share')->group(function () {
    Route::get('{platform:platform}/{post:slug}', [ShareController::class, 'share'])
        ->name('share');
});


/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
require __DIR__ . '/groups/guest.php';

/*
|--------------------------------------------------------------------------
| Document Routes
|--------------------------------------------------------------------------
*/
require __DIR__ . '/groups/document.php';

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Auth::routes(['verify' => true]);

/*
|--------------------------------------------------------------------------
| CMS Routes
|--------------------------------------------------------------------------
*/
require __DIR__ . '/groups/cms.php';
