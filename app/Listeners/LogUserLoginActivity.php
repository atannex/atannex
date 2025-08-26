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
     * This method is automatically triggered when the UserLoggedIn event is dispatched.
     * It delegates logging responsibility to the User model (via TracksUserActivity trait).
     *
     * @param UserLoggedIn $event  The dispatched event instance containing the user and request.
     * @return void
     */
    public function handle(UserLoggedIn $event): void
    {
        try {
            // Call trait method on the user model to log login details (IP, device, timestamps)
            $event->user->logLogin($event->request);
        } catch (\Exception $e) {
            // Fail gracefully: log error but don't break the login process
            Log::error("Failed to log login activity for user {$event->user->id}: {$e->getMessage()}");
        }
    }
}
