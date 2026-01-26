<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Posts\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Global override for post moderators.
     */
    public function before(?User $user, string $ability): bool|null
    {
        if (! $user) {
            return null;
        }

        if ($user->can('posts.moderate')) {
            return true;
        }

        return null;
    }

    /**
     * View any posts.
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
        if ($post->is_private) {
            return $user?->can('posts.view.private') ?? false;
        }

        return true;
    }

    /**
     * Create posts.
     */
    public function create(User $user): bool
    {
        return $user->can('posts.create');
    }

    /**
     * Update posts.
     */
    public function update(User $user, Post $post): bool
    {
        return
            // Edit any post
            $user->can('posts.edit.any')

            // Edit own post
            || (
                $user->can('posts.edit.own')
                && $post->user_id === $user->id
            );
    }

    /**
     * Publish posts.
     */
    public function publish(User $user, Post $post): bool
    {
        return $user->can('posts.publish');
    }

    /**
     * Schedule posts.
     */
    public function schedule(User $user, Post $post): bool
    {
        return $user->can('posts.schedule');
    }

    /**
     * Delete posts.
     */
    public function delete(User $user, Post $post): bool
    {
        return
            // Delete any post
            $user->can('posts.delete.any')

            // Delete own post
            || (
                $user->can('posts.delete.own')
                && $post->user_id === $user->id
            );
    }

    /**
     * Restore deleted posts.
     */
    public function restore(User $user, Post $post): bool
    {
        return $user->can('posts.restore');
    }

    /**
     * Force delete posts.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return $user->can('posts.force_delete');
    }
}
