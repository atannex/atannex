<?php

namespace App\Notifications;

use App\Models\Comments\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Comment $comment
    ) {}

    /**
     * Notification channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // $url = route('comments.show', $this->comment->commentable->id);

        return (new MailMessage)
            ->subject('New Comment Posted')
            ->greeting("Hello {$notifiable->name},")
            ->line("A new comment has been posted on: {$this->comment->commentable->title}")
            ->line("Comment: \"{$this->comment->comment}\"")
            // ->action('View Comment', $url)
            ->line('Stay engaged with the discussion!');
    }

    /**
     * Database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'comment_id'       => $this->comment->id,
            'comment_text'     => $this->comment->comment,
            'commenter_name'   => $this->comment->user?->name ?? 'Guest',
            'commentable_id'   => $this->comment->commentable->id,
            'commentable_type' => get_class($this->comment->commentable),
            // 'url'              => route('comments.show', $this->comment->commentable->id),
        ];
    }
}
