<?php

namespace Atannex\Enables;

use Carbon\Carbon;
use App\Enums\Flag;
use Illuminate\Database\Eloquent\Builder;

trait EnableScope
{

    /**
     * Scope to only published posts.
     *
     * @param Builder $query
     * @param string $type 'global' for global posts, 'non-global' for non-global posts
     * @return Builder
     */
    public function scopePublished(Builder $query, string $type): Builder
    {
        if ($type === 'global') {

            $query->where('is_global', true)
                ->where('flag', Flag::PUBLISHED);
        } elseif ($type === 'non-global') {

            $query->where('is_global', false)
                ->where('flag', Flag::PUBLISHED);
        } else {

            $query->whereNotNull('published_at')
                ->where('published_at', '<=', now());
        }

        return $query;
    }

    /**
     * Scope to order by the `order` column.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }

    /**
     * Scope a query to filter posts between two dates.
     */
    public function scopeBetweenDates(Builder $query, ?Carbon $start, ?Carbon $end): Builder
    {
        if ($start && $end) {
            return $query->whereBetween('published_at', [$start, $end]);
        }

        if ($start) {
            return $query->where('published_at', '>=', $start);
        }

        if ($end) {
            return $query->where('published_at', '<=', $end);
        }

        return $query;
    }

    /**
     * Scope to filter by a specific flag.
     */
    public function scopeFlagged(Builder $query, Flag $flag): Builder
    {
        return $this->applyWhere($query, 'flag', $flag);
    }

    /**
     * Scope for active posts.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for posts scheduled in the future.
     */
    public function scopeIsFuture(Builder $query, string $column = 'scheduled_at'): Builder
    {
        return $query->where($column, '>', Carbon::now());
    }

    /**
     * Scope for posts in the past or now.
     */
    public function scopeIsPast(Builder $query, string $column = 'scheduled_at'): Builder
    {
        return $query->where($column, '<=', Carbon::now());
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
}
