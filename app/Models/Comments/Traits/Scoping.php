<?php

namespace App\Models\Comments\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait Scoping
 *
 * Provides query scopes for filtering and retrieving comments in a comment system.
 * Supports filtering by approval status and comment hierarchy, as well as eager loading related data.
 */
trait Scoping
{
    /**
     * Scope a query to only include approved comments.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include pending comments.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include top-level comments (comments without a parent).
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeTopLevel(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope a query to eager load all replies and the associated user.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithAllReplies(Builder $query): Builder
    {
        return $query->with(['allReplies', 'user']);
    }
}
