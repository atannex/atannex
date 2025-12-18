<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Atannex\Concerns\HasUserTracking;

class TrackUserActivity
{
    use HasUserTracking;

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        $this->trackActivity('route_access', [
            'route'     => $request->route()?->getName(),
            'url'       => $request->fullUrl(),
            'method'    => $request->method(),
            'timezone'  => $user?->timezone,
        ]);

        return $next($request);
    }
}
