<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ContactMessageSubmitted;
use App\Jobs\SendAdminContactEmails;

class DispatchAdminContactEmails
{
    public function handle(ContactMessageSubmitted $event): void
    {
        SendAdminContactEmails::dispatch($event->contact);
    }
}
