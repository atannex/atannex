<?php

namespace App\Http\Controllers\Auth\Traits;

use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait ThrottlesPerUser
{
    /**
     * Apply per-user + IP throttling.
     *
     * @param Request $request
     * @param int $maxAttempts
     * @param int $decayMinutes
     * @param callable|null $nextCallable
     * @return mixed
     */
    protected function throttle(Request $request, int $maxAttempts = 5, int $decayMinutes = 1, callable $nextCallable = null)
    {
        /** @var RateLimiter $limiter */
        $limiter = app(RateLimiter::class);

        $key = $this->throttleKey($request);

        if ($limiter->tooManyAttempts($key, $maxAttempts)) {
            return response()->json([
                'message' => 'Too many attempts. Please try again later.'
            ], 429);
        }

        // Hit the limiter only if it's an actual action (like POST request)
        if ($request->isMethod('post')) {
            $limiter->hit($key, $decayMinutes * 60);
        }

        return $nextCallable ? $nextCallable($request) : null;
    }

    /**
     * Generate a unique throttle key per user + IP.
     *
     * @param Request $request
     * @param string|null $identifier Optional identifier (like email)
     * @return string
     */
    protected function throttleKey(Request $request, ?string $identifier = null)
    {
        $userId = $request->user()->id ?? 'guest';
        $ip = $request->ip();

        $identifierPart = $identifier ?: ($request->input('email') ?? 'guest');

        return Str::lower("throttle|{$userId}|{$identifierPart}|{$ip}");
    }
}
