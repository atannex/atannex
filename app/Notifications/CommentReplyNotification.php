<?php

namespace App\Notifications;

use App\Models\Comments\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class CommentReplyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Comment $comment
    ) {}

    /**
     * Get the notification delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // $url = route('comments.show', $this->comment->commentable->id);

        return (new MailMessage)
            ->subject('Someone replied to your comment')
            ->greeting("Hello {$notifiable->name},")
            ->line("{$this->comment->user?->name} replied to your comment:")
            ->line("\"{$this->comment->comment}\"")
            // ->action('View Reply', $url)
            ->line('Thank you for engaging with our content!');
    }

    /**
     * Get the array / database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'comment_id'      => $this->comment->id,
            'comment_text'    => $this->comment->comment,
            'commenter_name'  => $this->comment->user?->name ?? 'Guest',
            'commentable_id'  => $this->comment->commentable->id,
            'commentable_type' => get_class($this->comment->commentable),
            // 'url'             => route('comments.show', $this->comment->commentable->id),
        ];
    }
}
