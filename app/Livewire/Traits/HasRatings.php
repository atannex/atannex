<?php

namespace App\Livewire\Traits;

use App\Models\Interactions\Rating;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

/**
 * Trait HasRatings
 *
 * Provides functionality for models to handle rating interactions, including creating, averaging, and retrieving ratings.
 *
 * @package App\Livewire\Traits
 */
trait HasRatings
{
    /**
     * Get the ratings associated with the model.
     *
     * @return MorphMany
     */
    public function ratings(): MorphMany
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    /**
     * Add or update a rating for the model by the authenticated user.
     *
     * @param int $value The rating value (must be between 1 and 5).
     * @return bool Returns true if the rating was created or updated, false if the user is not authenticated or the value is invalid.
     */
    public function rate(int $value): bool
    {
        if (!Auth::check() || $value < 1 || $value > 5) {
            return false;
        }

        $this->ratings()->updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'rating' => $value,
                'rated_at' => now(),
            ]
        );

        return true;
    }

    /**
     * Get the average rating for the model.
     *
     * @return float|null The average rating or null if no ratings exist.
     */
    public function averageRating(): ?float
    {
        return $this->ratings()->avg('rating');
    }

    /**
     * Get the total number of ratings for the model.
     *
     * @return int
     */
    public function ratingCount(): int
    {
        return $this->ratings()->count();
    }

    /**
     * Get the rating given by the authenticated user.
     *
     * @return int|null The user's rating or null if not rated or user is not authenticated.
     */
    public function userRating(): ?int
    {
        if (!Auth::check()) {
            return null;
        }

        return $this->ratings()->where('user_id', Auth::id())->value('rating');
    }
}
