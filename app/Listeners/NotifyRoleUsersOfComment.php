<?php

namespace App\Listeners;

use App\Events\NewComment;
use App\Jobs\NotifyUsersOfComment;

class NotifyRoleUsersOfComment
{
    public function handle(NewComment $event): void
    {
        NotifyUsersOfComment::dispatch($event->comment);
    }
}
