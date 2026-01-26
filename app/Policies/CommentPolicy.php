<?php

namespace App\Policies;

use App\Models\Comments\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Global override for comment moderators.
     */
    public function before(?User $user, string $ability): bool|null
    {
        if (! $user) {
            return null;
        }

        // Full comment moderation override
        if ($user->can('comments.moderate')) {
            return true;
        }

        return null;
    }

    /**
     * Anyone can create comments.
     */
    public function create(?User $user): bool
    {
        return true;
    }

    /**
     * Update comment.
     */
    public function update(User $user, Comment $comment): bool
    {
        return
            // Edit any comment
            $user->can('comments.edit.any')

            // Edit own comment
            || (
                $user->can('comments.edit.own')
                && $comment->user_id === $user->id
            );
    }

    /**
     * Delete comment.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return
            // Delete any comment
            $user->can('comments.delete.any')

            // Delete own comment
            || (
                $user->can('comments.delete.own')
                && $comment->user_id === $user->id
            );
    }

    /**
     * Restore deleted comments.
     */
    public function restore(User $user, Comment $comment): bool
    {
        return $user->can('comments.restore');
    }

    /**
     * Force delete comments.
     */
    public function forceDelete(User $user, Comment $comment): bool
    {
        return $user->can('comments.force_delete');
    }

    /**
     * React to comments.
     */
    public function react(User $user): bool
    {
        return $user->can('comments.react');
    }
}
