<?php

namespace App\Listeners;

use App\Events\UserLoggedIn;
use Illuminate\Support\Facades\Log;

/**
 * Class LogUserLoginActivity
 *
 * Listener for handling user login activity logging.
 *
 * @package App\Listeners
 */
class LogUserLoginActivity
{
    /**
     * Handle the UserLoggedIn event.
     *
     * @param UserLoggedIn $event
     * @return void
     */
    public function handle(UserLoggedIn $event): void
    {
        try {
            $event->user->logLogin($event->request);
        } catch (\Exception $e) {
            Log::error("Failed to log login activity for user {$event->user->id}: {$e->getMessage()}");
        }
    }
}
