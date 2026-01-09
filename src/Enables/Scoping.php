<?php

namespace Atannex\Enables;

use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

/**
 * Reusable query scopes for content-driven Eloquent models.
 */
trait Scoping
{
    /**
     * Build a query for popular published items ordered by most recently published and limited by count.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The Eloquent query builder instance being scoped.
     * @param int $limit Maximum number of results to return.
     * @return \Illuminate\Database\Eloquent\Builder The modified query builder ordered by `published_at` descending and constrained to `$limit` results.
     */
    public function scopePopular(Builder $query, int $limit = 5): Builder
    {
        return $query->published()
            // ->withCount(['comments', 'likes'])
            // ->selectRaw('posts.*, (views * 2 + comments_count * 3 + likes_count) as popularity_score')
            // ->orderByDesc('popularity_score')
            ->orderByDesc('published_at')
            ->limit($limit);
    }

    /**
     * Filter the query to content marked as active editor picks.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance.
     * @return \Illuminate\Database\Eloquent\Builder Builder constrained to editor picks whose `editor_pick_at` is <= now and whose `editor_pick_expires` is null or > now.
     */
    public function scopeActiveEditorPick($query)
    {
        return $query->where('is_editor_pick', true)
            ->where('editor_pick_at', '<=', now())
            ->where(function ($q) {
                $q->whereNull('editor_pick_expires')
                    ->orWhere('editor_pick_expires', '>', now());
            });
    }

    /**
     * Filter the query to records with the given flag value.
     *
     * @param Builder $query The query builder instance.
     * @param string $flag The flag enum value to filter by.
     * @return Builder The modified query builder.
     */
    protected function scopeFlagged(Builder $query, string $flag): Builder
    {
        return $query->where('flag', $flag);
    }

    /**
     * Restricts the query to models that have a publication timestamp in the past or present.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The Eloquent query builder to scope.
     * @return \Illuminate\Database\Eloquent\Builder The builder filtered to records where `published_at` is set and `published_at` is less than or equal to now.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Limit the query to records with a creation timestamp that is present and not in the future.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance to constrain.
     * @return \Illuminate\Database\Eloquent\Builder The query builder constrained to records where `created_at` is set and `created_at` <= now.
     */
    public function scopeCreated(Builder $query): Builder
    {
        return $query->whereNotNull('created_at')
            ->where('created_at', '<=', now());
    }

    /**
         * Restricts the query to posts marked as global and that have been created.
         *
         * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance.
         * @return \Illuminate\Database\Eloquent\Builder The modified query builder containing only global posts with a set creation time.
         */
    public function scopeGlobal(Builder $query): Builder
    {
        return $query->created()->where('is_global', true);
    }

    /**
     * Filter the query to records that are not marked as global.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The Eloquent query builder instance.
     * @return \Illuminate\Database\Eloquent\Builder The modified query builder.
     */
    public function scopeNonGlobal(Builder $query): Builder
    {
        return $query->created()->where('is_global', false);
    }

    /**
     * Limit the query to posts currently marked as breaking and not expired.
     *
     * Filters posts where `is_breaking` is true, `breaking_at` is less than or equal to now,
     * and `breaking_expires` is either null or greater than now.
     *
     * @return Builder The query builder constrained to breaking posts.
     */
    public function scopeBreaking(Builder $query): Builder
    {
        return $query->where('is_breaking', true)
            ->where('breaking_at', '<=', now())
            ->where(function (Builder $q) {
                $q->whereNull('breaking_expires')
                    ->orWhere('breaking_expires', '>', now());
            });
    }

    /**
     * Filter the query to records marked as active.
     *
     * @return Builder The query builder filtered to records where `is_active` is true.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Filter the query to records scheduled for a future time.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance.
     * @param string $column The datetime column to compare; defaults to 'scheduled_at'.
     * @return \Illuminate\Database\Eloquent\Builder The modified query constrained to rows where the specified column is greater than now.
     */
    public function scopeFuture(Builder $query, string $column = 'scheduled_at'): Builder
    {
        return $query->where($column, '>', now());
    }

    /**
         * Filter records whose given timestamp column is in the past or present.
         *
         * @param \Illuminate\Database\Eloquent\Builder $query The query builder to modify.
         * @param string $column The timestamp column to compare; defaults to `scheduled_at`.
         * @return \Illuminate\Database\Eloquent\Builder The query builder filtered to records where the specified column is less than or equal to the current time.
         */
    public function scopePast(Builder $query, string $column = 'scheduled_at'): Builder
    {
        return $query->where($column, '<=', now());
    }

    /**
     * Filter the query to records whose `published_at` falls between two dates (inclusive).
     *
     * The provided Carbon instances are normalized to the start of the start day and the end of the end day.
     *
     * @param Carbon $start Start date; normalized to the start of the day.
     * @param Carbon $end End date; normalized to the end of the day.
     * @return Builder The query builder constrained to `published_at` between the given dates.
     */
    public function scopeBetweenDates(Builder $query, Carbon $start, Carbon $end): Builder
    {
        return $query->whereBetween('published_at', [
            $start->startOfDay(),
            $end->endOfDay(),
        ]);
    }

    /**
         * Order query results by the `order` column.
         *
         * @param string $direction Direction to sort: `'asc'` for ascending or `'desc'` for descending.
         * @return Builder The query builder ordered by the `order` column.
         */
    public function scopeOrdered(Builder $query, string $direction = 'asc'): Builder
    {
        return $query->orderBy('order', $direction);
    }
}