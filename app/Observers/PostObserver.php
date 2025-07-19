<?php

namespace App\Observers;

use Carbon\Carbon;
use App\Models\Posts\Post;
use App\Enums\Flag;

class PostObserver
{
    /**
     * Handle the Post "saving" event.
     *
     * @param  \App\Models\Posts\Post  $post
     * @return void
     */
    public function saving(Post $post): void
    {
        $now = Carbon::now();

        if ($post->scheduled_at && $post->scheduled_at->isFuture()) {

            $post->flag = Flag::SCHEDULED;

            $post->published_at = null;
        } elseif ($post->scheduled_at && $post->scheduled_at->isPast()) {

            $post->flag = Flag::PUBLISHED;

            $post->published_at = $post->published_at ?? $now;
        } else {
            $post->flag = $post->flag ?? Flag::DRAFT;
        }
    }
}
