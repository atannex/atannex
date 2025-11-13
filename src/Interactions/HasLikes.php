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
 * Supports soft-deleted likes and restores on re-like.
 */
trait HasLikes
{
    /**
     * Define a polymorphic one-to-many relationship with the Like model.
     * Includes soft-deleted likes to support restoration.
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable')->withTrashed();
    }

    /**
     * Query builder for retrieving the authenticated user's like record,
     * including soft-deleted entries.
     *
     * @return Builder
     */
    protected function userLikeQuery()
    {
        return $this->likes()->where('user_id', Auth::id());
    }

    /**
     * Register a "like" from the currently authenticated user.
     * If the like exists and is soft-deleted, it is restored.
     * Otherwise, a new like is created or updated.
     */
    public function like(): void
    {
        $like = $this->userLikeQuery()->first();

        if ($like?->trashed()) {
            $like->restore();
            $like->touch('liked_at');
        } else {
            $this->likes()->updateOrCreate(
                ['user_id' => Auth::id()],
                ['liked_at' => now()]
            );
        }
    }

    /**
     * Soft-delete the like from the authenticated user.
     * If already soft-deleted, no action is taken.
     */
    public function unlike(): void
    {
        $like = $this->userLikeQuery()->first();

        if ($like && ! $like->trashed()) {
            $like->delete();
        }
    }

    /**
     * Get the total count of non-deleted likes for this model.
     */
    public function likesCount(): int
    {
        return $this->likes()
            ->whereNull('deleted_at')
            ->count();
    }

    /**
     * Check whether the current model is liked by the authenticated user.
     * Excludes soft-deleted likes.
     */
    public function isLikedByUser(): bool
    {
        return $this->userLikeQuery()
            ->whereNull('deleted_at')
            ->exists();
    }
}
