<?php

namespace App\Policies;

use App\Filament\Traits\HasVisibilityRules;
use App\Models\Comments\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;
    use HasVisibilityRules;

    /**
     * Global override for admins & super admins.
     */
    public function before(?User $user, string $ability): bool|null
    {
        if (static::canSeeModerationContent()) {
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
     * Comment owners can update their own comments.
     */
    public function update(?User $user, Comment $comment): bool
    {
        return $user !== null && $comment->user_id === $user->id;
    }

    /**
     * Comment owners can delete their own comments.
     */
    public function delete(?User $user, Comment $comment): bool
    {
        return $this->update($user, $comment);
    }

    /**
     * Only authenticated users can react.
     */
    public function react(?User $user): bool
    {
        return $user !== null;
    }
}
