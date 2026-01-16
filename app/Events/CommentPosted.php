<?php

namespace App\Events;

use App\Models\Comments\Comment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentPosted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Comment $comment
    ) {}
}
