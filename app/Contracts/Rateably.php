<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Interface RateableContract
 *
 * Contract for models that support identifier-based ratings.
 */
interface Rateably
{
    /**
     * Polymorphic ratings relationship.
     */
    public function ratings(): MorphMany;

    /**
     * Determine if the given identifier has rated this model.
     */
    public function hasRating(?string $identifier): bool;

    /**
     * Get total number of ratings.
     */
    public function ratingCount(): int;

    /**
     * Get average rating value.
     */
    public function averageRating(): float;

    /**
     * Add or update a rating.
     */
    public function setRating(?string $identifier, int $rating, ?string $comment = null, ?string $ipAddress = null): void;

    /**
     * Remove rating associated with identifier.
     */
    public function removeRating(?string $identifier): void;
}
