<?php

namespace Atannex\Enables;

use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

/**
 * Reusable query scopes for content-driven Eloquent models.
 */
trait Scoping
{
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
     * Scope: Only active editor picks.
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
     * Scope: Filter by any Flag enum value.
     */
    protected function scopeFlagged(Builder $query, string $flag): Builder
    {
        return $query->where('flag', $flag);
    }

    /**
     * Scope: Only posts that have been published.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope: Only posts that have been created.
     */
    public function scopeCreated(Builder $query): Builder
    {
        return $query->whereNotNull('created_at')
            ->where('created_at', '<=', now());
    }

    /**
     * Scope: Only posts marked as global.
     */
    public function scopeGlobal(Builder $query): Builder
    {
        return $query->created()->where('is_global', true);
    }

    /**
     * Scope: Only posts not marked as global.
     */
    public function scopeNonGlobal(Builder $query): Builder
    {
        return $query->created()->where('is_global', false);
    }

    /**
     * Scope: Only breaking posts.
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
     * Scope: Only active posts.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Posts scheduled for the future.
     */
    public function scopeFuture(Builder $query, string $column = 'scheduled_at'): Builder
    {
        return $query->where($column, '>', now());
    }

    /**
     * Scope: Posts scheduled for the past or present.
     */
    public function scopePast(Builder $query, string $column = 'scheduled_at'): Builder
    {
        return $query->where($column, '<=', now());
    }

    /**
     * Scope: Filter posts between two dates (Carbon instances, always available).
     */
    public function scopeBetweenDates(Builder $query, Carbon $start, Carbon $end): Builder
    {
        return $query->whereBetween('published_at', [
            $start->startOfDay(),
            $end->endOfDay(),
        ]);
    }

    /**
     * Scope: Order posts by a specified column.
     */
    public function scopeOrdered(Builder $query, string $direction = 'asc'): Builder
    {
        return $query->orderBy('order', $direction);
    }
}
