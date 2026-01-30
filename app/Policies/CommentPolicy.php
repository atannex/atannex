<?php

namespace App\Policies;

use App\Enums\Status;
use App\Models\Comments\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Super admin can bypass all checks.
     */
    public function before(User $user): bool
    {
        return $user->hasRole('super admin');
    }

    /**
     * Determine whether the user can create a comment.
     */
    public function create(User $user): bool
    {
        return $this->isActiveUser($user);
    }

    /**
     * Determine whether the user can update a comment.
     * Users can edit their own comments within a grace period, admins/moderators can edit any.
     */
    public function update(User $user, Comment $comment): bool
    {
        $isOwner = $comment->user_id === $user->id;
        $withinEditWindow = $comment->created_at->diffInMinutes(now()) <= 30;

        return $user->hasAnyRole(['admin', 'moderator']) || ($isOwner && $withinEditWindow && $this->isActiveUser($user));
    }

    /**
     * Determine whether the user can delete a comment.
     * Owners can delete their own comments; moderators/admins can delete any.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->hasAnyRole(['admin', 'moderator']) || ($comment->user_id === $user->id && $this->isActiveUser($user));
    }

    /**
     * Determine whether the user can react to a comment.
     */
    public function react(User $user): bool
    {
        return $this->isActiveUser($user);
    }

    /**
     * Determine whether the user can report a comment.
     * Users cannot report their own comments.
     */
    public function report(User $user, Comment $comment): bool
    {
        return $this->isActiveUser($user) && $comment->user_id !== $user->id;
    }

    /**
     * Helper: Check if user is active and has a valid role.
     */
    protected function isActiveUser(User $user): bool
    {
        return $user->flag === Status::ACTIVE && $user->hasAnyRole(['user', 'moderator', 'admin']);
    }
}
