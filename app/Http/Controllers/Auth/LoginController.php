<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users when the intended URL fails.
     *
     * @return string
     */
    public function redirectTo()
    {
        return route('home');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
        $this->middleware('throttle:5,1')->only('login');
    }

    /**
     * Customize the maximum number of login attempts.
     *
     * @return int
     */
    protected function maxAttempts()
    {
        return 5;
    }

    /**
     * Customize the number of minutes to throttle for.
     *
     * @return int
     */
    protected function decayMinutes()
    {
        return 1;
    }
}
