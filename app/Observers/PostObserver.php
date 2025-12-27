<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Posts\Post;
use App\Support\ClearsPostShowCache;

/**
 * Clears post show caches when a Post changes.
 */
final class PostObserver
{
    use ClearsPostShowCache;

    public function created(Post $post): void
    {
        $this->clearPostShowCache($post);
    }

    public function updated(Post $post): void
    {
        // Clear cache for old slug if it changed
        if ($post->wasChanged('slug_path')) {
            $this->clearPostShowCacheForSlug(
                (string) $post->getOriginal('slug_path')
            );
        }

        $this->clearPostShowCache($post);
    }

    public function deleted(Post $post): void
    {
        $this->clearPostShowCache($post);
    }

    public function restored(Post $post): void
    {
        $this->clearPostShowCache($post);
    }
}
