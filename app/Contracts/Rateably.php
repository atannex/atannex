<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Comments\Rateable;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Interface Rateably
 *
 * Defines the contract for any model that can be rated by users.
 * Implementing this interface ensures that the model supports full rating functionality,
 * including CRUD operations, aggregates, and per-user rating checks.
 */
interface Rateably
{
    /**
     * Get all ratings associated with this model.
     *
     * @return MorphMany A polymorphic one-to-many relationship to the Rateable model.
     */
    public function ratings(): MorphMany;

    /**
     * Get the total number of ratings for this model.
     *
     * @return int Number of ratings.
     */
    public function ratingCount(): int;

    /**
     * Get the average rating value for this model.
     *
     * @return float Average of all rating values.
     */
    public function averageRating(): float;

    /**
     * Check if this model has any ratings.
     *
     * @return bool True if there is at least one rating, false otherwise.
     */
    public function hasRatings(): bool;

    /**
     * Get the rating provided by a specific user.
     *
     * @param int|null $userId The ID of the user. Defaults to null (can use current user).
     * @return Rateable|null The user's rating, or null if none exists.
     */
    public function ratingByUser(?int $userId = null): ?Rateable;

    /**
     * Determine if a specific user has rated this model.
     *
     * @param int|null $userId The ID of the user. Defaults to null (can use current user).
     * @return bool True if the user has rated, false otherwise.
     */
    public function hasUserRated(?int $userId = null): bool;

    /**
     * Add a rating for this model.
     *
     * @param int $rating The numeric rating value.
     * @param string|null $comment Optional comment associated with the rating.
     * @param int|null $userId The ID of the user adding the rating. Defaults to null.
     * @param string|null $ipAddress Optional IP address of the user for logging or analytics.
     * @return Rateable The newly created rating instance.
     */
    public function addRating(
        int $rating,
        ?string $comment = null,
        ?int $userId = null,
        ?string $ipAddress = null
    ): Rateable;

    /**
     * Remove a rating from a specific user.
     *
     * @param int|null $userId The ID of the user whose rating should be removed. Defaults to null.
     * @return bool True if a rating was successfully removed, false otherwise.
     */
    public function removeRating(?int $userId = null): bool;

    /**
     * Get a breakdown of all ratings, typically grouped by rating value.
     *
     * Example return: [5 => 10, 4 => 3, 3 => 2]
     *
     * @return array An associative array showing counts of each rating value.
     */
    public function ratingsBreakdown(): array;
}
