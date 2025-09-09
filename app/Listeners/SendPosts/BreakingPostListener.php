<?php

namespace App\Listeners\SendPosts;

use App\Events\Posts\BreakingPost;
use App\Models\User;
use App\Notifications\BreakingPostNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BreakingPostListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  BreakingPost  $event
     * @return void
     */
    public function handle(BreakingPost $event)
    {
        $post = $event->post;

        User::chunk(100, function($users) use ($post) {
            Notification::send($users, new BreakingPostNotification($post));
        });
    }
}
