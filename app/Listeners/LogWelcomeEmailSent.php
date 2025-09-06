<?php

namespace App\Listeners;

use App\Events\UserWelcomeEmailSent;
use Illuminate\Support\Facades\Log;

class LogWelcomeEmailSent
{
    /**
     * Handle the event.
     *
     * @param UserWelcomeEmailSent $event
     * @return void
     */
    public function handle(UserWelcomeEmailSent $event)
    {
        Log::info('Welcome email sent to user', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
        ]);
    }
}
