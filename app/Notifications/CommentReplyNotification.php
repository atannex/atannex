<?php

namespace App\Notifications;

use App\Models\Comments\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentReplyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Comment $comment
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $parent = $this->comment->parent;

        return (new MailMessage)
            ->subject('New Reply to a Comment')
            ->greeting('Hello ' . $notifiable->name)
            ->line("{$this->comment->author_name} replied to a comment.")
            ->line('Reply:')
            ->line('"' . str($this->comment->comment)->limit(150) . '"')
            ->action(
                'View Reply',
                $this->comment->commentable->url
            )
            ->line('You are receiving this notification because of your role.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'comment_id'        => $this->comment->id,
            'parent_comment_id' => $this->comment->parent_id,
            'author'            => $this->comment->author_name,
            'excerpt'           => str($this->comment->comment)->limit(100),
        ];
    }
}
