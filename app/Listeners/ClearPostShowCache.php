<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\PostContentChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

/**
 * Clears cached fragments for post show pages.
 *
 * Runs asynchronously to avoid blocking HTTP requests.
 */
final class ClearPostShowCache implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(PostContentChanged $event): void
    {
        $post   = $event->post;
        $postId = (int) $post->id;

        Cache::forget($this->key("post_module.slug.{$post->slug_path}"));
        Cache::forget($this->key("posts.related.{$postId}"));
        Cache::forget($this->key("posts.recent.{$postId}"));
        Cache::forget($this->key("tags.post.{$postId}"));
        Cache::forget($this->key("post.navigation.{$postId}"));
    }

    /**
     * Versioned cache key helper.
     */
    private function key(string $key): string
    {
        return "view.show.v1.{$key}";
    }
}
