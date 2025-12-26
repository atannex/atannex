<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Auth::routes(['verify' => true]);

/*
|--------------------------------------------------------------------------
| Protected Routes
| Middleware: Onboarded (auth + verified + password.confirm)
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'verified',
    'password.confirm'
])->group(function () {});
