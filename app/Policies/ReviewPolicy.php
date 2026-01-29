<?php

namespace App\Policies;

use App\Models\Posts\Review;
use App\Models\User;

class ReviewPolicy
{
    /**
     * Determine if a user (or guest) can create a review.
     */
    public function create(?User $user): bool
    {
        return true; // Guests allowed
    }

    /**
     * Determine if a user can update a review.
     */
    public function update(User $user, Review $review): bool
    {
        // Author can update only if the review model allows it
        return $review->user_id === $user->id
            && $review->canEdit();
    }

    /**
     * Determine if a user can delete a review.
     */
    public function delete(User $user, Review $review): bool
    {
        // Only the author can delete
        return $review->user_id === $user->id;
    }
}
