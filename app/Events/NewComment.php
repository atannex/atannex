<?php

namespace App\Events;

use App\Models\Comments\Comment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewComment
{
    use Dispatchable, SerializesModels;

    /**
     * The comment instance.
     *
     * @var \App\Models\Comments\Comment
     */
    public Comment $comment;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Comments\Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }
}
