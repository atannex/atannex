<?php

namespace App\Notifications;

use App\Models\Comments\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Comment $comment
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Comment Posted')
            ->greeting('Hello ' . $notifiable->name)
            ->line("{$this->comment->author_name} posted a new comment.")
            ->line('Comment:')
            ->line('"' . str($this->comment->comment)->limit(150) . '"')
            ->action(
                'View Comment',
                $this->comment->commentable->url
            )
            ->line('You are receiving this notification because of your role.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'comment_id' => $this->comment->id,
            'author'    => $this->comment->author_name,
            'excerpt'   => str($this->comment->comment)->limit(100),
        ];
    }
}
