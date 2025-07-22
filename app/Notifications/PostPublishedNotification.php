<?php

namespace App\Notifications;

use App\Models\Posts\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class PostPublishedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The post that was published.
     *
     * @var Post
     */
    protected $post;

    /**
     * Create a new notification instance.
     *
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
        $this->queue = 'notifications';
        $this->delay = 10;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        try {
            $postUrl = URL::route('posts.show', ['post' => $this->post->id]);

            return (new MailMessage)
                ->subject("New Post Published: {$this->post->title}")
                ->greeting("Hello {$notifiable->name}!")
                ->line("A new post has been published: **{$this->post->title}**")
                ->line(Str::limit(strip_tags($this->post->content), 200, '...'))
                ->action('Read the Post', $postUrl)
                ->line('Thank you for staying updated with our content!');
        } catch (\Throwable $e) {
            Log::error('Failed to generate mail notification for post published', [
                'post_id' => $this->post->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get the array representation of the notification for database storage.
     *
     * @param mixed $notifiable
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            'post_id' => $this->post->id,
            'title' => $this->post->title,
            'excerpt' => Str::limit(strip_tags($this->post->content), 100, '...'),
            'published_at' => $this->post->published_at->toISOString(),
            'type' => 'post_published',
        ];
    }
}
