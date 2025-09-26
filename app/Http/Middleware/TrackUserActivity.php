<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Atannex\Traits\HasUserTracking;

class TrackUserActivity
{
    use HasUserTracking;

    /**
     * Handle an incoming request and track user activity if authenticated.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $metadata = [
                'route_name' => $request->route()->getName(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'timestamp' => now()->toDateTimeString(),
                'timezone' => $request->user()->timezone,
            ];

            $this->trackActivity('route_access', $metadata);
        }

        return $next($request);
    }
}
