<?php

namespace Atannex\Enables;

use App\Enums\Flag;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * Reusable query scopes for content-driven Eloquent models.
 */
trait Scoping
{
    /**
     * Scope: Only posts that have been published.
     */
    protected function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope: Only posts that have been created (sanity check for timestamps).
     */
    protected function scopeCreated(Builder $query): Builder
    {
        return $query->whereNotNull('created_at')
            ->where('created_at', '<=', now());
    }

    /**
     * Scope: Posts marked as global and already created.
     */
    protected function scopeGlobal(Builder $query): Builder
    {
        return $query->created()
            ->where('is_global', true);
    }

    /**
     * Scope: Posts not marked as global.
     */
    protected function scopeNonGlobal(Builder $query): Builder
    {
        return $query->created()
            ->where('is_global', false);
    }

    /**
     * Scope: Breaking news posts with an active breaking status.
     */
    protected function scopeBreaking(Builder $query): Builder
    {
        return $query->where('is_breaking', true)
            ->where(function (Builder $q) {
                $q->whereNull('breaking_until')
                    ->orWhere('breaking_until', '>=', now());
            });
    }

    /**
     * Scope: Editor’s pick posts.
     */
    protected function scopeEditorPick(Builder $query): Builder
    {
        return $query->published()
            ->where('flag', Flag::EDITORIAL_PICK);
    }

    /**
     * Scope: Order posts by a specified direction.
     *
     * @param  string  $direction  Sort direction: 'asc' or 'desc'
     */
    protected function scopeOrdered(Builder $query, string $direction = 'asc'): Builder
    {
        return $query->orderBy('order', $direction);
    }

    /**
     * Scope: Filter posts between two dates (inclusive).
     */
    protected function scopeBetweenDates(Builder $query, ?Carbon $start = null, ?Carbon $end = null): Builder
    {
        if ($start && $end) {
            return $query->whereBetween('published_at', [
                $start->copy()->startOfDay(),
                $end->copy()->endOfDay(),
            ]);
        }

        if ($start instanceof Carbon) {
            return $query->where('published_at', '>=', $start->copy()->startOfDay());
        }

        if ($end instanceof Carbon) {
            return $query->where('published_at', '<=', $end->copy()->endOfDay());
        }

        return $query;
    }

    /**
     * Scope: Filter based on enum flags.
     */
    protected function scopeFlagged(Builder $query, Flag $flag): Builder
    {
        return $query->where('flag', $flag->value);
    }

    /**
     * Scope: Only active posts.
     */
    protected function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Posts scheduled for the future.
     *
     * @param  string  $column  Schedule date column (default: 'scheduled_at')
     */
    protected function scopeFuture(Builder $query, string $column = 'scheduled_at'): Builder
    {
        return $query->where($column, '>', now());
    }

    /**
     * Scope: Posts scheduled for the past or the present.
     *
     * @param  string  $column  Schedule date column (default: 'scheduled_at')
     */
    protected function scopePast(Builder $query, string $column = 'scheduled_at'): Builder
    {
        return $query->where($column, '<=', now());
    }
}
