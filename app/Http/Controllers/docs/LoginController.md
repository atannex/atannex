
# Login

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\Traits\ThrottlesPerUser;

class LoginController extends Controller
{
    use AuthenticatesUsers, ThrottlesPerUser;

    public function redirectTo()
    {
        return route('home');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');

        // Apply per-user + IP throttling to login
        $this->middleware(function ($request, $next) {
            return $this->throttle($request, 5, 1, fn($req) => $next($req));
        })->only('login');
    }

    public function username()
    {
        return 'email';
    }
}
