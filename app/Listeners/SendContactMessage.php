<?php

namespace App\Listeners;

use App\Events\ContactMessageCreated;
use App\Jobs\SendContactEmail;

class SendContactMessage
{
    /**
     * Handle the event.
     */
    public function handle(ContactMessageCreated $event)
    {
        SendContactEmail::dispatch($event->contact);
    }
}
