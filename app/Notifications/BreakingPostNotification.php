<?php

namespace App\Notifications;

use App\Models\Posts\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BreakingPostNotification extends Notification
{
    use Queueable;

    protected $post;

    /**
     * Create a new notification instance.
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Determine the notification delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Email representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Breaking News: ' . $this->post->title)
                    ->line('A new breaking post has been published:')
                    ->line($this->post->title)
                    ->action('Read Post', url('/posts/' . $this->post->id))
                    ->line('Stay updated with the latest news.');
    }

    /**
     * Database representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'post_id' => $this->post->id,
            'title'   => $this->post->title,
            'message' => 'A new breaking post has been published!',
        ];
    }
}
