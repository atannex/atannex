<?php

namespace App\Events\Posts\Posts;

use App\Models\Posts\Post;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class BreakingPost
{
    use Dispatchable;
    use SerializesModels;

    public $post;

    /**
     * Create a new event instance.
     *
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }
}
