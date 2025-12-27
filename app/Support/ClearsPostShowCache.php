<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Posts\Post;
use Illuminate\Support\Facades\Cache;

/**
 * Shared cache invalidation logic for post show pages.
 */
trait ClearsPostShowCache
{
    protected function clearPostShowCache(Post $post): void
    {
        $postId = (int) $post->id;

        Cache::forget($this->cacheKey("post_module.slug.{$post->slug_path}"));
        Cache::forget($this->cacheKey("posts.related.{$postId}"));
        Cache::forget($this->cacheKey("posts.recent.{$postId}"));
        Cache::forget($this->cacheKey("tags.post.{$postId}"));
        Cache::forget($this->cacheKey("post.navigation.{$postId}"));
    }

    protected function clearPostShowCacheForSlug(string $slug): void
    {
        Cache::forget($this->cacheKey("post_module.slug.{$slug}"));
    }

    protected function cacheKey(string $key): string
    {
        return "view.show.v1.{$key}";
    }
}
