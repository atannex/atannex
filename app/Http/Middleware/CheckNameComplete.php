<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to check if the user's profile name is complete.
 */
class CheckNameComplete
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request The incoming HTTP request
     * @param Closure(Request): Response $next The next middleware in the stack
     * @return Response The HTTP response
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Authentication required.');
        }

        if (empty($user->name) && !empty($user->name_token)) {
            return redirect()->route('name.index', ['token' => $user->name_token])
                ->with('info', 'Please complete your profile by providing a name.');
        }

        return $next($request);
    }
}
