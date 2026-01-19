<?php

declare(strict_types=1);

namespace Atannex\Services;

use App\Models\User;
use App\Models\Comments\Comment;
use Illuminate\Support\Facades\DB;

class CommentReactionService
{
    /**
     * React to a comment.
     *
     * If the user already has the same reaction, it will be removed.
     * Otherwise, it will be added or switched.
     *
     * @param Comment $comment
     * @param User $user
     * @param string $type Either 'like' or 'dislike'
     *
     */
    public function react(Comment $comment, User $user, string $type): void
    {
        DB::transaction(function () use ($comment, $user, $type): void {

            if (
                ($type === 'like' && $comment->isLikedBy($user)) ||
                ($type === 'dislike' && $comment->isDislikedBy($user))
            ) {
                $comment->removeReaction($user);
                return;
            }

            match ($type) {
                'like'    => $comment->like($user),
                'dislike' => $comment->dislike($user),
            };
        });
    }
}
