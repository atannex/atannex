<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Posts\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Global override for super admins and moderators.
     */
    public function before(?User $user): bool
    {
        return $user->hasAnyRole(['super admin', 'moderator']);
    }

    /**
     * View any posts (publicly accessible).
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * View a single post.
     */
    public function view(?User $user, Post $post): bool
    {
        return $user?->hasAnyRole(['admin', 'editor', 'super admin', 'user']);
    }

    /**
     * Create posts.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['user', 'editor', 'admin', 'super admin']);
    }

    /**
     * Update posts.
     */
    public function update(User $user, Post $post): bool
    {
        $isOwner = $post->user_id === $user->id;

        // Admins and editors can edit any post, owners can edit their own
        return $user->hasAnyRole(['admin', 'editor', 'super admin']) || $isOwner;
    }

    /**
     * Publish posts.
     */
    public function publish(User $user, Post $post): bool
    {
        return $user->hasAnyRole(['editor', 'admin', 'super admin']);
    }

    /**
     * Schedule posts.
     */
    public function schedule(User $user, Post $post): bool
    {
        return $user->hasAnyRole(['editor', 'admin', 'super admin']);
    }

    /**
     * Delete posts.
     */
    public function delete(User $user, Post $post): bool
    {
        $isOwner = $post->user_id === $user->id;

        // Admins can delete any post, owners can delete their own
        return $user->hasAnyRole(['admin', 'super admin']) || $isOwner;
    }

    /**
     * Restore deleted posts.
     */
    public function restore(User $user, Post $post): bool
    {
        return $user->hasAnyRole(['admin', 'super admin']);
    }

    /**
     * Force delete posts.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return $user->hasRole('super admin');
    }
}
