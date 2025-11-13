<?php

namespace Atannex\Enables;

use App\Enums\Flag;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * Common query scopes for content models.
 */
trait Scoping
{
    /**
     * Only published posts (general).
     */
    protected function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Only global posts (published + global flag).
     */
    protected function scopeGlobal(Builder $query): Builder
    {
        return $query->published()
            ->where('is_global', true);
    }

    /**
     * Only non-global posts (published + non-global flag).
     */
    protected function scopeNonGlobal(Builder $query): Builder
    {
        return $query->published()
            ->where('is_global', false);
    }

    /**
     * Breaking news posts (active breaking status).
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
     * Editor pick posts.
     */
    protected function scopeEditorPick(Builder $query): Builder
    {
        return $query->published()
            ->where('flag', Flag::EDITORIAL_PICK);
    }

    /**
     * Ordered posts (by `order` column).
     *
     * @param  string  $direction  'asc' or 'desc'
     */
    protected function scopeOrdered(Builder $query, string $direction = 'asc'): Builder
    {
        return $query->orderBy('order', $direction);
    }

    /**
     * Filter posts between two dates (inclusive).
     */
    protected function scopeBetweenDates(Builder $query, ?Carbon $start = null, ?Carbon $end = null): Builder
    {
        if ($start && $end) {
            return $query->whereBetween('published_at', [
                $start->startOfDay(),
                $end->endOfDay(),
            ]);
        }

        if ($start instanceof Carbon) {
            return $query->where('published_at', '>=', $start->startOfDay());
        }

        if ($end instanceof Carbon) {
            return $query->where('published_at', '<=', $end->endOfDay());
        }

        return $query;
    }

    /**
     * Filter by enum-based flag.
     */
    protected function scopeFlagged(Builder $query, Flag $flag): Builder
    {
        return $query->where('flag', $flag->value);
    }

    /**
     * Active posts.
     */
    protected function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Posts scheduled for the future.
     *
     * @param  string  $column  Column to check (default: 'scheduled_at')
     */
    protected function scopeFuture(Builder $query, string $column = 'scheduled_at'): Builder
    {
        return $query->where($column, '>', now());
    }

    /**
     * Posts scheduled in the past or right now.
     *
     * @param  string  $column  Column to check (default: 'scheduled_at')
     */
    protected function scopePast(Builder $query, string $column = 'scheduled_at'): Builder
    {
        return $query->where($column, '<=', now());
    }
}
