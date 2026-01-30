<?php

namespace App\Policies;

use App\Enums\Status;
use App\Models\Posts\Review;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    use HandlesAuthorization;

    /**
     * Super admin can do everything.
     */
    public function before(User $user): ?bool
    {
        return $user->hasRole('super admin');
    }

    /**
     * Determine if an authenticated and active user can create a review.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['user', 'moderator', 'admin']) && $user->flag === Status::ACTIVE;
    }

    /**
     * Determine if a user can update a review.
     * Users can edit their own reviews within allowed conditions.
     */
    public function update(User $user, Review $review): bool
    {
        $isOwner = $review->user_id === $user->id;
        $canEdit = $review->canEdit(); // Assuming this checks time window or other business rules

        return $user->hasAnyRole(['admin', 'moderator']) // Admins and moderators can edit any
            || ($isOwner && $canEdit && $user->flag === Status::ACTIVE);
    }

    /**
     * Determine if a user can delete a review.
     * Owners can delete their own; admins and moderators can delete any.
     */
    public function delete(User $user, Review $review): bool
    {
        return $user->hasAnyRole(['admin', 'moderator'])
            || ($review->user_id === $user->id && $user->flag === Status::ACTIVE);
    }

    /**
     * Optional: Allow reporting reviews for moderation.
     */
    public function report(User $user, Review $review): bool
    {
        return $user->hasRole(['user', 'moderator', 'admin'])
            && $user->flag === Status::ACTIVE
            && $review->user_id !== $user->id;
    }
}
