<?php

namespace App\Listeners;

use Throwable;
use App\Enums\Flag;
use App\Models\Posts\Post;
use App\Events\PostPublished;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PostPublishedNotification;

class HandlePostPublished implements ShouldQueue
{
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'listeners';

    /**
     * The time (in seconds) before the job should be processed.
     *
     * @var int
     */
    public $delay = 10;

    /**
     * Handle the event.
     *
     * @param PostPublished $event
     * @return void
     */
    public function handle(PostPublished $event)
    {
        try {
            $post = $event->post;

            Log::info('Processing published post', [
                'post_id' => $post->id,
                'title' => $post->title,
                'published_at' => $post->published_at->toISOString(),
            ]);

            // Send notification to subscribers
            $this->sendNotifications($post);

            // Update cache
            $this->updateCache($post);

            // Trigger webhook
            $this->triggerWebhook($post);

            // Update metrics
            $this->updateMetrics($post);
        } catch (Throwable $e) {
            Log::error('Failed to process post published event', [
                'post_id' => $event->post->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Send notifications to subscribers about the published post.
     *
     * @param \App\Models\Posts\Post $post
     * @return void
     */
    protected function sendNotifications($post): void
    {
        try {
            // Example: Notify users subscribed to the post's category
            $users = $post->category?->subscribers ?? collect();
            Notification::send($users, new PostPublishedNotification($post));

            Log::debug('Notifications sent for published post', [
                'post_id' => $post->id,
                'user_count' => $users->count(),
            ]);
        } catch (Throwable $e) {
            Log::warning('Failed to send notifications for post', [
                'post_id' => $post->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update cache for the published post.
     *
     * @param \App\Models\Posts\Post $post
     * @return void
     */
    protected function updateCache($post): void
    {
        try {
            // Invalidate and refresh cache for recent posts
            Cache::forget('recent_posts');
            Cache::remember('recent_posts', now()->addHours(1), function () {
                return Post::where('flag', Flag::PUBLISHED)
                    ->latest()
                    ->take(10)
                    ->get();
            });

            // Cache individual post
            Cache::put("post_{$post->id}", $post, now()->addDays(1));

            Log::debug('Cache updated for published post', [
                'post_id' => $post->id,
            ]);
        } catch (Throwable $e) {
            Log::warning('Failed to update cache for post', [
                'post_id' => $post->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Trigger webhook for external services.
     *
     * @param \App\Models\Posts\Post $post
     * @return void
     */
    protected function triggerWebhook($post): void
    {
        try {
            $webhookUrl = config('services.webhook.post_published');

            if ($webhookUrl) {
                Http::post($webhookUrl, [
                    'post_id' => $post->id,
                    'title' => $post->title,
                    'published_at' => $post->published_at->toISOString(),
                ]);

                Log::debug('Webhook triggered for published post', [
                    'post_id' => $post->id,
                    'webhook_url' => $webhookUrl,
                ]);
            }
        } catch (Throwable $e) {
            Log::warning('Failed to trigger webhook for post', [
                'post_id' => $post->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update metrics for the published post.
     *
     * @param \App\Models\Posts\Post $post
     * @return void
     */
    protected function updateMetrics($post): void
    {
        try {
            // Example: Increment publication counter
            Cache::increment('posts_published_total');

            // Update category-specific metrics
            if ($post->category_id) {
                Cache::increment("posts_published_category_{$post->category_id}");
            }

            Log::debug('Metrics updated for published post', [
                'post_id' => $post->id,
            ]);
        } catch (Throwable $e) {
            Log::warning('Failed to update metrics for post', [
                'post_id' => $post->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
