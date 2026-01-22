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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class NotifyUsersOfComment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Comment $comment
    ) {}

    public function handle(): void
    {
        $this->notifyReplyAuthor();
        $this->notifyNewTopLevelCommentSubscribers();
    }

    /**
     * Notify the author of the parent comment (if it's a reply).
     */
    private function notifyReplyAuthor(): void
    {
        if (! $this->comment->parent_id) {
            return;
        }

        $parent = $this->comment->parent;

        // Skip if no parent user or if the replier is replying to themselves
        if (! $parent?->user || $parent->user_id === $this->comment->user_id) {
            return;
        }

        $parent->user->notify(new CommentReplyNotification($this->comment));
    }

    /**
     * Handle notifications for new top-level comments:
     * - All registered users (except author)
     * - All previous guest commenters (distinct emails)
     * - The current guest commenter (confirmation)
     */
    private function notifyNewTopLevelCommentSubscribers(): void
    {
        if ($this->comment->parent_id) {
            return; // Only top-level comments trigger this
        }

        $notification = new NewCommentNotification($this->comment);

        // 1. Registered users
        $this->notifyRegisteredUsers($notification);

        // 2. Previous guest commenters
        $this->notifyPreviousGuests($notification);

        // 3. Current guest (if applicable – confirmation email)
        $this->notifyCurrentGuest($notification);
    }

    private function notifyRegisteredUsers(NewCommentNotification $notification): void
    {
        $users = User::where('id', '!=', $this->comment->user_id)->get();

        if ($users->isNotEmpty()) {
            Notification::send($users, $notification);
        }
    }

    private function notifyPreviousGuests(NewCommentNotification $notification): void
    {
        $emails = $this->getPreviousGuestEmails();

        if ($emails->isEmpty()) {
            return;
        }

        Notification::route('mail', $emails->toArray())
            ->notify($notification);
    }

    private function notifyCurrentGuest(NewCommentNotification $notification): void
    {
        if (! $this->comment->is_guest || ! $this->comment->guest_email) {
            return;
        }

        // Avoid double-sending if the current guest was already in previous guests
        // (common when editing or if duplicate comment somehow)
        if ($this->getPreviousGuestEmails()->contains($this->comment->guest_email)) {
            return;
        }

        Notification::route('mail', $this->comment->guest_email)
            ->notify($notification);
    }

    /**
     * Fetch distinct guest emails from previous top-level comments.
     * Customize the query scope based on your model's relationships.
     */
    private function getPreviousGuestEmails(): Collection
    {
        $query = Comment::query()
            ->whereNull('parent_id')                    // only top-level
            ->where('id', '!=', $this->comment->id)     // exclude current comment
            ->where('is_guest', true)
            ->whereNotNull('guest_email')
            ->where('guest_email', '!=', $this->comment->guest_email); // exclude current if exists

        // === IMPORTANT: Add your grouping logic here ===
        // Examples:
        // If comments belong to a post/article (polymorphic):
        // ->where('commentable_id',   $this->comment->commentable_id)
        // ->where('commentable_type', $this->comment->commentable_type)
        //
        // Or if using a post_id column:
        // ->where('post_id', $this->comment->post_id)

        return $query->pluck('guest_email')
            ->filter()          // remove any null/empty values
            ->unique()
            ->values();
    }
}
