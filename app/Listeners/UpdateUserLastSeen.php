<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class UpdateUserLastSeen
 *
 * Listener that updates the "last seen" timestamp whenever
 * a user is authenticated.
 *
 * Using a queued listener:
 * - Avoids slowing down login/auth process.
 * - Handles activity tracking asynchronously.
 * - Improves scalability for high-traffic apps.
 */
class UpdateUserLastSeen implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create a new event listener instance.
     */
    public function __construct()
    {
        // No special initialization required
    }

    /**
     * Handle the event.
     *
     * @param Authenticated $event
     * @return void
     */
    public function handle(Authenticated $event)
    {
        $user = $event->user;

        if (method_exists($user, 'updateLastSeenTimestamp')) {
            try {
                $user->updateLastSeenTimestamp();
            } catch (\Throwable $e) {
                Log::error("Failed to update last seen for user {$user->id}: {$e->getMessage()}");
            }
        }
    }
}
