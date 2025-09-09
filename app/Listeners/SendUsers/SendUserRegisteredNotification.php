<?php

namespace App\Listeners\SendUsers;

use App\Events\Users\UserCreated;
use App\Jobs\ProcessUsers\RegisteredJob;

class SendUserRegisteredNotification
{
    /**
     * Handle the event.
     */
    public function handle(UserCreated $event): void
    {
        RegisteredJob::dispatch($event->user);
    }
}
