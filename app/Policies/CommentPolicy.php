<?php

namespace App\Policies;

use App\Models\Comments\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create comments.
     *
     * Anyone can create comments (authenticated users or guests).
     */
    public function create(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the comment.
     *
     * - Authenticated users: only if they own the comment (user_id matches)
     * - Guests: only if the current session's guest_comment_token matches the comment's token
     */
    public function update(?User $user, Comment $comment): bool
    {
        // Authenticated user: must be the owner
        if ($user) {
            return $comment->user_id === $user->id;
        }

        // Guest user: must have matching guest token in session
        if ($comment->is_guest) {
            $sessionToken = session('guest_comment_token');

            return $sessionToken !== null && $sessionToken === $comment->guest_token;
        }

        // Guest trying to edit a non-guest comment → denied
        return false;
    }

    /**
     * Determine whether the user can delete the comment.
     *
     * Same rules as update (owner only).
     */
    public function delete(?User $user, Comment $comment): bool
    {
        // Reuse update logic – delete follows the same ownership rules
        return $this->update($user, $comment);
    }
}
