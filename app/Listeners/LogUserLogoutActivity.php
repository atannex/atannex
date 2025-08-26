<?php

namespace App\Listeners;

use App\Events\UserLoggedOut;
use Illuminate\Support\Facades\Log;

/**
 * Class LogUserLogoutActivity
 *
 * Listener for handling user logout activity logging.
 *
 * @package App\Listeners
 */
class LogUserLogoutActivity
{
    /**
     * Handle the UserLoggedOut event.
     *
     * @param UserLoggedOut $event
     * @return void
     */
    public function handle(UserLoggedOut $event): void
    {
        try {
            $event->user->logLogout();
        } catch (\Exception $e) {
            Log::error("Failed to log logout activity for user {$event->user->id}: {$e->getMessage()}");
        }
    }
}
