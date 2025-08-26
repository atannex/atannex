<?php

namespace App\Listeners;

use App\Events\UserLoggedIn;
use Illuminate\Support\Facades\Log;

/**
 * Class LogUserLoginActivity
 *
 * Listener responsible for logging user login activity.
 *
 * Why use a listener instead of inline logic?
 * - Separation of concerns: keeps authentication logic clean.
 * - Reusability: same listener can be reused across different login flows.
 * - Testability: listener can be unit tested independently.
 */
class LogUserLoginActivity
{
    /**
     * Handle the UserLoggedIn event.
     *
     * Delegates logging to the User model (via TracksUserActivity trait).
     *
     * @param UserLoggedIn $event The dispatched event instance containing the user and request.
     * @return void
     */
    public function handle(UserLoggedIn $event): void
    {
        try {
            $event->user->logLogin($event->request);
            Log::info("Login activity logged for user {$event->user->id} from IP {$event->request->ip()}");
        } catch (\Exception $e) {
            Log::error("Failed to log login activity for user {$event->user->id} from IP {$event->request->ip()}: {$e->getMessage()}");
        }
    }
}
