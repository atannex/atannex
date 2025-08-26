<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * Class UpdateUserLastSeen
 *
 * Listener that updates the last seen timestamp for a user whenever
 * the Authenticated event is fired.
 */
class UpdateUserLastSeen implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create a new event listener instance.
     */
    public function __construct()
    {
        // No special setup needed
    }

    /**
     * Handle the event.
     *
     * @param Authenticated $event
     */
    public function handle(Authenticated $event): void
    {
        try {
            if ($event->user) {
                // Call the trait method which queues the update
                $event->user->updateLastSeenTimestamp();
            }
        } catch (\Exception $e) {
            Log::error("Failed to update last seen for user {$event->user->id}: {$e->getMessage()}");
        }
    }
}
