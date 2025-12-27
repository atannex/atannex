<?php

namespace App\Events;

use App\Models\Posts\Post;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Fired whenever something that affects a post show page changes.
 */
final class PostContentChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Post $post
    ) {}
}
