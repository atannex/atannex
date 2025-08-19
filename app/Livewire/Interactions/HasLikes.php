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
     *
     * @return MorphMany
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable')->withTrashed();
    }

    /**
     * Add or restore a like for the authenticated user.
     *
     * If a soft-deleted like exists, it is restored. Otherwise, a new like is created.
     * The unique constraint ensures no duplicate active likes.
     *
     * @return bool Returns true if the like was created/restored, false otherwise.
     * @throws QueryException If there's a database error during the operation.
     */
    public function like(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        try {
            $like = $this->likes()
                ->withTrashed()
                ->where('user_id', Auth::id())
                ->first();

            if ($like && $like->trashed()) {
                $like->restore();
                $like->update(['liked_at' => now()]);
                return true;
            }

            $this->likes()->firstOrCreate([
                'user_id' => Auth::id(),
                'likeable_id' => $this->id,
                'likeable_type' => get_class($this),
            ], [
                'liked_at' => now(),
            ]);

            return true;
        } catch (QueryException $e) {
            return false;
        }
    }

    /**
     * Remove a like (soft delete) for the authenticated user.
     *
     * @return bool Returns true if the like was soft-deleted, false if it doesn't exist or user is not authenticated.
     */
    public function unlike(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $like = $this->likes()
            ->where('user_id', Auth::id())
            ->first();

        if ($like && !$like->trashed()) {
            $like->delete();
            return true;
        }

        return false;
    }

    /**
     * Get the total number of active (non-deleted) likes for the model.
     *
     * @return int The total count of active likes.
     */
    public function likesCount(): int
    {
        return $this->likes()->whereNull('deleted_at')->count();
    }

    /**
     * Check if the authenticated user has an active like on the model.
     *
     * @return bool True if the authenticated user has an active like, false otherwise.
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
     *
     * @return Carbon|null The liked_at timestamp or null if not liked, deleted, or user is not authenticated.
     */
    public function likedAt(): ?Carbon
    {
        if (!Auth::check()) {
            return null;
        }

        return $this->likes()
            ->where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->value('liked_at') ?? null;
    }
}
