<?php

namespace App\Http\Controllers\Auth;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;
use App\Events\UserLoggedIn;
use App\Events\UserLoggedOut;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
        $this->middleware('throttle:'.config('auth.login_throttle', '3,1'))->only('login');
    }

    /**
     * Handle a successful login attempt.
     *
     * Dispatches UserLoggedIn event and cleans expired sessions.
     *
     * @param Request $request
     * @param mixed $user
     * @return RedirectResponse
     */
    protected function authenticated(Request $request, $user)
    {
        try {
            event(new UserLoggedIn($user, $request));
            $user->cleanExpiredSessions();
        } catch (Exception $exception) {
            Log::error(sprintf('Error handling login for user %s: %s', $user->id, $exception->getMessage()));
        }

        return redirect()->intended($this->redirectTo);
    }

    /**
     * Handle a failed login attempt.
     *
     * Logs failed login attempt for security tracking.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        try {
            Log::warning(sprintf('Failed login attempt for email %s from IP %s', $request->email, $request->ip()));
            // Optionally: Dispatch a UserFailedLogin event (requires new event/listener)
        } catch (Exception $exception) {
            Log::error('Error logging failed login attempt: ' . $exception->getMessage());
        }

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * Dispatches UserLoggedOut event before logging out.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request)
    {
        try {
            $user = $this->guard()->user();
            if ($user) {
                event(new UserLoggedOut($user));
            }
        } catch (Exception $exception) {
            Log::error('Error handling logout for user: ' . $exception->getMessage());
        }

        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect($this->redirectTo);
    }
}
