<?php

namespace Atannex\Interactions;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Interactions\Rating;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait HasRatings
 *
 * Provides rating functionality for models using polymorphic relations.
 * Assumes data (e.g., Auth user, ratings) is always available and valid.
 */
trait HasRatings
{
    public const RATING_MIN = 1;

    public const RATING_MAX = 5;

    /**
     * Define a polymorphic one-to-many relationship with the Rating model.
     *
     * @return MorphMany
     */
    public function ratings(): MorphMany
    {
        return $this->morphMany(Rating::class, 'rateable')->withTrashed();
    }

    /**
     * Query for the current authenticated user's rating.
     *
     * @return Builder
     */
    protected function userRatingQuery()
    {
        return $this->ratings()->where('user_id', Auth::id());
    }

    /**
     * Retrieve the rating record for the current user.
     * Assumes the record always exists.
     *
     * @return Rating
     */
    protected function userRatingRecord(): ?Rating
    {
        return $this->userRatingQuery()->first();
    }

    /**
     * Create or update the rating for the current user.
     * Restores the rating if it was soft-deleted.
     *
     * @param int $value
     * @return bool
     */
    public function rate(int $value): bool
    {
        if ($value < self::RATING_MIN || $value > self::RATING_MAX) {
            return false;
        }

        $rating = $this->userRatingRecord();

        if ($rating?->trashed()) {
            $rating->restore();
            $rating->update([
                'rating' => $value,
                'rated_at' => now(),
            ]);
            return true;
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
     * Soft-delete the current user's rating.
     * Assumes the rating is not already trashed.
     *
     * @return bool
     */
    public function unrate(): bool
    {
        $rating = $this->userRatingRecord();

        $rating->delete();
        return true;
    }

    /**
     * Calculate the average rating (excluding soft-deleted entries).
     * Returns rounded float to two decimal places.
     *
     * @return float
     */
    public function averageRating(): float
    {
        return round(
            $this->ratings()
                ->whereNull('deleted_at')
                ->avg('rating'),
            2
        );
    }

    /**
     * Count the number of active (non-deleted) ratings.
     *
     * @return int
     */
    public function ratingCount(): int
    {
        return $this->ratings()
            ->whereNull('deleted_at')
            ->count();
    }

    /**
     * Get the current user's rating value.
     *
     * @return int
     */
    public function userRating(): int
    {
        return (int) $this->userRatingQuery()
            ->whereNull('deleted_at')
            ->value('rating');
    }
}
