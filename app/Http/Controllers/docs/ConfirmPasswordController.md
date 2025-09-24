# ConfirmPasswordController

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ConfirmsPasswords;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\Traits\ThrottlesPerUser;

class ConfirmPasswordController extends Controller
{
    use ConfirmsPasswords, ThrottlesPerUser;

    public function redirectTo()
    {
        return route('home');
    }

    public function __construct()
    {
        $this->middleware('auth');

        // Apply per-user + IP throttling to password confirmation
        $this->middleware(function ($request, $next) {
            return $this->throttle($request, 5, 1, fn($req) => $next($req));
        })->only('confirm');
    }
}
