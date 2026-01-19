<?php

namespace App\Jobs;

use App\Models\Comments\Comment;
use App\Models\User;
use App\Notifications\CommentReplyNotification;
use App\Notifications\NewCommentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class NotifyUsersOfComment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Comment $comment;

    /**
     * Create a new job instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Skip unapproved comments
        if (!$this->comment->is_approved) {
            return;
        }

        // Determine which notification class to use
        $notificationClass = $this->comment->isReply()
            ? CommentReplyNotification::class
            : NewCommentNotification::class;

        // Chunk users to avoid memory overload
        User::query()
            ->whereHas('roles') // adjust if you need specific roles
            ->where('id', '!=', $this->comment->user_id)
            ->select('id', 'name', 'email')
            ->chunkById(100, function ($users) use ($notificationClass) {

                foreach ($users as $user) {

                    // Use a transaction to prevent race conditions
                    DB::transaction(function () use ($user, $notificationClass) {

                        // Atomic check to prevent duplicates
                        $alreadyNotified = $user->notifications()
                            ->where('type', $notificationClass)
                            ->where('data->comment_id', $this->comment->id)
                            ->exists();

                        if ($alreadyNotified) {
                            return;
                        }

                        // Send the notification
                        $user->notify(new $notificationClass($this->comment));
                    });
                }
            });
    }
}
