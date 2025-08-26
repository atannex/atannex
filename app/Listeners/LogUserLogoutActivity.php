<?php

namespace App\Listeners;

use App\Events\UserLoggedOut;
use Illuminate\Support\Facades\Log;

/**
 * Class LogUserLogoutActivity
 *
 * Listener responsible for recording logout activity.
 *
 * Why use a listener instead of inline logic?
 * - Separation of concerns: keeps authentication controller/service slim.
 * - Reusability: same listener can be used across web, API, or admin logouts.
 * - Fault tolerance: exceptions here won’t break the logout process.
 */
class LogUserLogoutActivity
{
    /**
     * Handle the UserLoggedOut event.
     *
     * Delegates logout activity recording to the User model
     * (via TracksUserActivity trait). Captures timestamp + last seen update.
     *
     * @param UserLoggedOut $event  The dispatched event containing the user instance.
     * @return void
     */
    public function handle(UserLoggedOut $event): void
    {
        try {
            // Update user activity record with logout time & last seen timestamp
            $event->user->logLogout();
        } catch (\Exception $e) {
            // Fail gracefully: log error for debugging/monitoring
            Log::error("Failed to log logout activity for user {$event->user->id}: {$e->getMessage()}");
        }
    }
}
