<?php

namespace App\Policies;

use App\Models\Posts\Review;
use App\Models\User;

class ReviewPolicy
{
    /**
     * Super admin can do everything.
     */
    public function before(User $user): bool|null
    {
        return $user->hasRole('Super Administrator') ? true : null;
    }

    /**
     * Determine if a user (or guest) can create a review.
     */
    public function create(?User $user): bool
    {
        return true;
    }

    /**
     * Determine if a user can update a review.
     */
    public function update(User $user, Review $review): bool
    {
        return $review->user_id === $user->id
            && $review->canEdit();
    }

    /**
     * Determine if a user can delete a review.
     */
    public function delete(User $user, Review $review): bool
    {
        return $review->user_id === $user->id;
    }
}
