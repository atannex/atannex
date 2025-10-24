<?php

namespace App\Listeners\Users;

use App\Events\Users\UserCreated;
use App\Jobs\Users\RegisteredJob;

class SendUserRegisteredNotification
{
    /**
     * Handle the event.
     */
    public function handle(UserCreated $event): void
    {
        dispatch(new RegisteredJob($event->user));
    }
}
