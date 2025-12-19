<?php

namespace Atannex\Interactions;

use App\Models\Interactions\Like;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

/**
 * Trait HasLikes
 *
 * Adds like/unlike functionality to models using a polymorphic relationship.
 * Likes are treated as state (no soft deletes).
 */
trait HasLikes
{
    /**
     * Define a polymorphic one-to-many relationship with the Like model.
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Query builder for retrieving the authenticated user's like record.
     */
    protected function userLikeQuery(): Builder
    {
        return $this->likes()
            ->getQuery()
            ->where('user_id', Auth::id());
    }

    /**
     * Register a "like" from the currently authenticated user.
     */
    public function like(): void
    {
        if (! Auth::check()) {
            return;
        }

        $this->likes()->firstOrCreate(
            ['user_id' => Auth::id()],
            ['liked_at' => now()]
        );
    }

    /**
     * Remove the like from the authenticated user.
     */
    public function unlike(): void
    {
        if (! Auth::check()) {
            return;
        }

        $this->userLikeQuery()->delete();
    }

    /**
     * Get the total count of likes for this model.
     */
    public function likesCount(): int
    {
        return $this->likes()->count();
    }

    /**
     * Check whether the current model is liked by the authenticated user.
     */
    public function isLikedByUser(): bool
    {
        if (! Auth::check()) {
            return false;
        }

        return $this->userLikeQuery()->exists();
    }
}
