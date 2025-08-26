<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class UpdateUserLastSeenMiddleware
 *
 * Middleware to update the "last seen" timestamp for authenticated users.
 * This ensures that the last activity is accurately tracked on every request.
 *
 * Key benefits over a queued listener:
 * - More accurate: updates on every authenticated request, not just login.
 * - Scalable: uses the queued job internally from the trait to avoid blocking.
 * - Safe: fails gracefully if the update cannot be queued.
 */
class UpdateUserLastSeen
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && method_exists($request->user(), 'updateLastSeenTimestamp')) {
            try {
                // Delegate the actual update to the trait, which queues the job
                $request->user()->updateLastSeenTimestamp();
            } catch (\Throwable $e) {
                // Log the exception but do not block the request
                Log::error("Failed to update last seen for user {$request->user()->id}: {$e->getMessage()}");
            }
        }

        return $next($request);
    }
}
