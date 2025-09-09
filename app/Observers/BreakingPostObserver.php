<?php

namespace App\Observers;

use App\Models\Posts\Post;
use App\Events\BreakingPost;
use App\Jobs\ProcessPosts\BreakingPostJob;

class BreakingPostObserver
{
    public function created(Post $post)
    {
        if ($post->is_breaking && $post->breaking_until) {
            event(new BreakingPost($post));

            BreakingPostJob::dispatch($post)
                ->delay($post->breaking_until->diffInSeconds(now()));
        }
    }

    public function updated(Post $post)
    {
        if ($post->is_breaking && $post->breaking_until && !$post->getOriginal('is_breaking')) {
            event(new BreakingPost($post));

            BreakingPostJob::dispatch($post)
                ->delay($post->breaking_until->diffInSeconds(now()));
        }
    }
}
