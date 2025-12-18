<?php

namespace App\Listeners;

use App\Models\Controls\Session;
use Illuminate\Auth\Events\Login;

class GuestActivityOnLogin
{
    public function handle(Login $event): void
    {
        $user      = $event->user;
        $sessionId = session()->getId();

        /**
         * Attach the current guest session to the user
         */
        $session = Session::where('id', $sessionId)
            ->whereNull('user_id')
            ->first();

        if (! $session) {
            return;
        }

        $session->update([
            'user_id' => $user->getAuthIdentifier(),
        ]);

        /**
         * Attach all guest activities in this session
         */
        $session->activities()
            ->whereNull('user_id')
            ->update([
                'user_id' => $user->getAuthIdentifier(),
            ]);
    }
}
