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
        $response = $next($request);

        if (
            ! $request->isMethod('GET') ||
            ! $request->route()?->getName() ||
            $request->is('storage/*') ||
            $request->is('assets/*') ||
            str_starts_with($request->path(), 'storage/')
        ) {
            return $response;
        }

        $this->trackActivity('route_access', [
            'route'    => $request->route()->getName(),
            'slug'     => $request->route('slug'),
            'path'     => $request->path(),
            'url'      => $request->fullUrl(),
            'method'   => $request->method(),
            'timezone' => $request->user()?->timezone,
        ]);

        return $response;
    }
}
