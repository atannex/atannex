<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserLoggedIn;
use Illuminate\Http\Request;
use App\Events\UserLoggedOut;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function redirectTo()
    {
        return route('home');
    }

    protected function authenticated(Request $request, $user)
    {
        // Fire login event
        event(new UserLoggedIn($user, $request));
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        $this->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user) {
            event(new UserLoggedOut($user));
        }

        return $this->loggedOut($request) ?: redirect('/');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}

