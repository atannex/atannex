<?php

namespace App\Livewire\Interactions;

use App\Models\Interactions\Like;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Database\QueryException;

/**
 * Trait HasLikes
 *
 * Provides functionality for models to handle like/unlike interactions using soft deletes,
 * avoiding duplicates, and managing like/unlike transitions in a polymorphic relationship.
 *
 * @package App\Livewire\Traits
 */
trait HasLikes
{
    /**
     * Get the likes associated with the model, including soft-deleted ones.
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable')->withTrashed();
    }

    /**
     * Fetch the authenticated user's like (including trashed).
     */
    protected function userLike(): ?Like
    {
        return Auth::check()
            ? $this->likes()->where('user_id', Auth::id())->first()
            : null;
    }

    /**
     * Add or restore a like for the authenticated user.
     */
    public function like(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        try {
            $like = $this->userLike();

            if ($like && $like->trashed()) {
                $like->restore();
                $like->update(['liked_at' => now()]);
                return true;
            }

            $this->likes()->firstOrCreate(
                [
                    'user_id' => Auth::id(),
                    'likeable_id' => $this->id,
                    'likeable_type' => static::class,
                ],
                ['liked_at' => now()]
            );

            return true;
        } catch (QueryException $e) {
            return false;
        }
    }

    /**
     * Remove a like (soft delete) for the authenticated user.
     */
    public function unlike(): bool
    {
        $like = $this->userLike();

        if ($like && !$like->trashed()) {
            $like->delete();
            return true;
        }

        return false;
    }

    /**
     * Get the total number of active (non-deleted) likes for the model.
     */
    public function likesCount(): int
    {
        return $this->likes()->whereNull('deleted_at')->count();
    }

    /**
     * Check if the authenticated user has an active like on the model.
     */
    public function isLikedByUser(): bool
    {
        return Auth::check() && $this->likes()
            ->where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->exists();
    }

    /**
     * Get the timestamp when the authenticated user liked the model.
     */
    public function likedAt(): ?Carbon
    {
        return Auth::check()
            ? $this->likes()
                ->where('user_id', Auth::id())
                ->whereNull('deleted_at')
                ->value('liked_at')
            : null;
    }
}
