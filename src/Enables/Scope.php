<?php

namespace Atannex\Enables;

use Carbon\Carbon;
use App\Enums\Flag;
use Illuminate\Database\Eloquent\Builder;

trait Scope
{
    /**
     * Scope to only published posts.
     *
     * @param string|null $type 'global'|'non-global'|null
     */
    protected function scopePublished(Builder $query, ?string $type = null): Builder
    {
        if ($type === 'global' || $type === 'non-global') {
            return $query->where('is_global', $type === 'global')
                ->where('flag', Flag::PUBLISHED);
        }

        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope to order by the `order` column.
     */
    protected function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }

    /**
     * Scope a query to filter posts between two dates.
     */
    protected function scopeBetweenDates(Builder $query, ?Carbon $start, ?Carbon $end): Builder
    {
        if ($start && $end) {
            return $query->whereBetween('published_at', [$start, $end]);
        }

        return $start instanceof Carbon
            ? $query->where('published_at', '>=', $start)
            : ($end instanceof Carbon ? $query->where('published_at', '<=', $end) : $query);
    }

    /**
     * Scope to filter by a specific flag.
     */
    protected function scopeFlagged(Builder $query, Flag $flag): Builder
    {
        return $this->applyWhere($query, 'flag', $flag);
    }

    /**
     * Scope for active posts.
     */
    protected function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for posts scheduled in the future.
     */
    protected function scopeIsFuture(Builder $query, string $column = 'scheduled_at'): Builder
    {
        return $this->applyDateComparison($query, $column, '>');
    }

    /**
     * Scope for posts in the past or now.
     */
    protected function scopeIsPast(Builder $query, string $column = 'scheduled_at'): Builder
    {
        return $this->applyDateComparison($query, $column, '<=');
    }

    /**
     * Protected helper to apply a where clause for enum-based columns.
     */
    protected function applyWhere(Builder $query, string $column, $value): Builder
    {
        if (is_object($value) && method_exists($value, 'value')) {
            $value = $value->value;
        }

        return $query->where($column, $value);
    }

    /**
     * Protected helper for date comparisons to reduce repeated code.
     */
    protected function applyDateComparison(Builder $query, string $column, string $operator): Builder
    {
        return $query->where($column, $operator, Carbon::now());
    }
}
