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
 * Provides functionality for models to handle like interactions, including creating, counting,
 * and checking likes in a polymorphic relationship.
 *
 * @package App\Livewire\Traits
 */
trait HasLikes
{
    /**
     * Get the likes associated with the model.
     *
     * @return MorphMany<Like>
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Add a like to the model for the authenticated user.
     *
     * @return bool Returns true if the like was created or already exists, false otherwise.
     * @throws QueryException If there's a database error during the operation.
     */
    public function like(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        try {
            $this->likes()->firstOrCreate([
                'user_id' => Auth::id(),
                'likeable_id' => $this->id,
                'likeable_type' => get_class($this),
                'liked_at' => now(),
            ]);

            return true;
        } catch (QueryException $e) {
            return false;
        }
    }

    /**
     * Get the total number of likes for the model.
     *
     * @return int The total count of likes.
     */
    public function likesCount(): int
    {
        return $this->likes()->count();
    }

    /**
     * Check if the authenticated user has liked the model.
     *
     * @return bool True if the authenticated user has liked the model, false otherwise.
     */
    public function isLikedByUser(): bool
    {
        return Auth::check() && $this->likes()->where('user_id', Auth::id())->exists();
    }

    /**
     * Get the timestamp when the authenticated user liked the model.
     *
     * @return Carbon|null The liked_at timestamp or null if not liked or user is not authenticated.
     */
    public function likedAt(): ?Carbon
    {
        if (!Auth::check()) {
            return null;
        }

        return $this->likes()->where('user_id', Auth::id())->value('liked_at') ?? null;
    }
}
