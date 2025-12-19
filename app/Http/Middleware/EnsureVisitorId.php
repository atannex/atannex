<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class EnsureVisitorId
{
    public function handle($request, Closure $next)
    {
        $visitorId = $request->cookie('visitor_id');

        if (!$visitorId) {
            $visitorId = (string) Str::uuid();

            Cookie::queue(
                cookie(
                    'visitor_id',
                    $visitorId,
                    60 * 24 * 730,
                    '/',
                    null,
                    true,
                    true,
                    false,
                    'lax'
                )
            );
        }

        app()->instance('visitor_id', $visitorId);

        return $next($request);
    }
}
