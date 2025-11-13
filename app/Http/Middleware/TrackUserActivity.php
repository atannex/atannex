<?php

namespace App\Http\Middleware;

use Atannex\Concerns\HasUserTracking;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackUserActivity
{
    use HasUserTracking;

    /**
     * Handle an incoming request and track the user's activity if authenticated.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = $request->user();

            $metadata = [
                'route_name' => $request->route()?->getName(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'timestamp' => now()->toDateTimeString(),
                'timezone' => $user?->timezone,
            ];

            $this->trackActivity('route_access', $metadata);
        }

        return $next($request);
    }
}
