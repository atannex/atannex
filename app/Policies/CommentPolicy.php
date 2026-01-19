<?php

namespace App\Policies;

use App\Models\Comments\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Anyone can create comments (authenticated or guest).
     */
    public function create(?User $user): bool
    {
        return true;
    }

    /**
     * Only the comment owner (authenticated user) can update.
     */
    public function update(?User $user, Comment $comment): bool
    {
        return $user !== null && $comment->user_id === $user->id;
    }

    /**
     * Only the comment owner (authenticated user) can delete.
     */
    public function delete(?User $user, Comment $comment): bool
    {
        return $this->update($user, $comment);
    }

    /**
     * Only authenticated users can react (like/dislike).
     */
    public function react(?User $user): bool
    {
        return $user !== null;
    }
}
