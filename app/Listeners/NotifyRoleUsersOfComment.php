<?php

namespace App\Listeners;

use App\Events\CommentPosted;
use App\Jobs\NotifyUsersOfComment;

class NotifyRoleUsersOfComment
{
    public function handle(CommentPosted $event): void
    {
        NotifyUsersOfComment::dispatch($event->comment);
    }
}
