<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\User;

/**
 * Interface Likeably
 *
 * Defines the contract for any model that can be "liked" or "disliked" by a user.
 * Implementing this interface ensures that the model supports basic reaction functionality.
 */
interface Likeably
{
    /**
     * Check if the given user has liked this model.
     *
     * @param User $user The user to check against.
     * @return bool True if the user has liked this model, false otherwise.
     */
    public function isLikedBy(User $user): bool;

    /**
     * Check if the given user has disliked this model.
     *
     * @param User $user The user to check against.
     * @return bool True if the user has disliked this model, false otherwise.
     */
    public function isDislikedBy(User $user): bool;

    /**
     * Record a "like" from the given user for this model.
     *
     * @param User $user The user performing the like action.
     */
    public function like(User $user): void;

    /**
     * Record a "dislike" from the given user for this model.
     *
     * @param User $user The user performing the dislike action.
     */
    public function dislike(User $user): void;

    /**
     * Remove any reaction (like or dislike) from the given user.
     *
     * @param User $user The user whose reaction should be removed.
     */
    public function removeReaction(User $user): void;
}
