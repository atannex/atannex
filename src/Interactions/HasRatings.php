<?php

namespace Atannex\Interactions;

use App\Models\Interactions\Rating;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

/**
 * Trait HasRatings
 *
 * Provides rating functionality for models using polymorphic relations.
 * Ratings are treated as state (no soft deletes).
 */
trait HasRatings
{
    public const RATING_MIN = 1;
    public const RATING_MAX = 5;

    /**
     * Define a polymorphic one-to-many relationship with the Rating model.
     */
    public function ratings(): MorphMany
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    /**
     * Query builder for the current authenticated user's rating.
     */
    protected function userRatingQuery(): Builder
    {
        return $this->ratings()
            ->getQuery()
            ->where('user_id', Auth::id());
    }

    /**
     * Create or update the rating for the current user.
     */
    public function rate(int $value): bool
    {
        if (! Auth::check()) {
            return false;
        }

        if ($value < self::RATING_MIN || $value > self::RATING_MAX) {
            return false;
        }

        $this->ratings()->updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'rating'   => $value,
                'rated_at' => now(),
            ]
        );

        return true;
    }

    /**
     * Remove the current user's rating.
     */
    public function unrate(): bool
    {
        if (! Auth::check()) {
            return false;
        }

        $this->userRatingQuery()->delete();

        return true;
    }

    /**
     * Calculate the average rating.
     */
    public function averageRating(): float
    {
        $average = $this->ratings()->avg('rating');

        return $average ? round($average, 2) : 0.0;
    }

    /**
     * Count the number of ratings.
     */
    public function ratingCount(): int
    {
        return $this->ratings()->count();
    }

    /**
     * Get the current user's rating value.
     */
    public function userRating(): ?int
    {
        return $this->userRatingQuery()->value('rating');
    }
}
