<?php

namespace Atannex\Enables;

use Carbon\Carbon;
use App\Enums\Flag;
use Illuminate\Database\Eloquent\Builder;

trait EnableScope
{
    /**
     * Scope a query to filter posts between two dates.
     *
     * @param Builder $query
     * @param Carbon $start
     * @param Carbon $end
     * @return Builder
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
     * Scope to only published pages.
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope to filter by given flag.
     *
     * @param Builder $query
     * @param Flag $flag
     * @return Builder
     */
    public function scopeFlagged(Builder $query, Flag $flag): Builder
    {
        return $this->applyWhere($query, 'flag', $flag);
    }

    /**
     * Scope to only active pages.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include breaking posts.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeIsBreaking(Builder $query): Builder
    {
        return $query->where('flag', Flag::BREAKING);
    }

    /**
     * Scope a query to only include breaking posts.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeIsEditorPick(Builder $query): Builder
    {
        return $query->where('flag', Flag::EDITORIAL_PICK);
    }

    /**
     * Scope a query to only include posts where a given datetime column is in the future.
     *
     * @param  Builder  $query
     * @param  string  $column
     * @return Builder
     */
    public function scopeIsFuture(Builder $query, string $column = 'scheduled_at'): Builder
    {
        return $query->where($column, '>', Carbon::now());
    }

    /**
     * Scope a query to only include posts where a given datetime column is in the past or now.
     *
     * @param  Builder  $query
     * @param  string  $column
     * @return Builder
     */
    public function scopeIsPast(Builder $query, string $column = 'scheduled_at'): Builder
    {
        return $query->where($column, '<=', Carbon::now());
    }

    /**
     * Protected helper to apply a where clause for enum-based columns.
     *
     * @param Builder $query
     * @param string $column
     * @param object|string|int $value
     * @return Builder
     */
    protected function applyWhere(Builder $query, string $column, $value): Builder
    {
        if (is_object($value) && method_exists($value, 'value')) {
            $value = $value->value;
        }

        return $query->where($column, $value);
    }
}
