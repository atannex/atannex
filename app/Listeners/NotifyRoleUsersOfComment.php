<?php

namespace App\Listeners;

use App\Events\CommentPosted;
use App\Models\User;
use App\Notifications\CommentReplyNotification;
use App\Notifications\NewCommentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class NotifyRoleUsersOfComment implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(CommentPosted $event): void
    {
        $comment = $event->comment;

        // Safety checks
        if (!$comment || !$comment->is_approved) {
            return;
        }

        // Prevent duplicate notifications on retries
        $lockKey = "notify:comment:{$comment->id}";

        Cache::lock($lockKey, 10)->get(function () use ($comment) {

            User::query()
                ->whereHas('roles')                  // ✅ any role from roles table
                ->where('id', '!=', $comment->user_id)
                ->select('id', 'name', 'email')
                ->chunkById(100, function ($users) use ($comment) {

                    foreach ($users as $user) {
                        $user->notify(
                            $comment->isReply()
                                ? new CommentReplyNotification($comment)
                                : new NewCommentNotification($comment)
                        );
                    }
                });
        });
    }
}
