<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserNameToken;

/**
 * Middleware to ensure users complete their profile using a token.
 */
class CheckNameComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Authentication required.');
        }

        $token = UserNameToken::where('user_id', $user->id)
            ->where('expires_at', '>', now())
            ->first();

        if (empty($user->name) && $token) {
            return redirect()->route('name.index', ['token' => $token->token])
                ->with('info', 'Please complete your profile by providing a name.');
        }

        return $next($request);
    }
}
