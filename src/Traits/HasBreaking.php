<?php

namespace Atannex\Traits;

use Illuminate\Support\Facades\Date;
use Illuminate\Database\Eloquent\Builder;

trait HasBreaking
{
    /**
     * Scope for active breaking posts.
     *
     * Uses the `breaking_until` column to determine if a post is still breaking.
     * Ensures breaking_until is greater than published_at and not expired.
     *
     * @param  Builder     $query
     * @param  string|null $timezone  User timezone (default: app timezone)
     * @return Builder
     */
    protected function scopeActiveBreaking(Builder $query, ?string $timezone = null): Builder
    {
        $now = Date::now($timezone ?? config('app.timezone'));

        return $query->published()
            ->where('breaking_until', '>=', $now)
            ->whereColumn('breaking_until', '>', 'published_at')
            ->breaking()
            ->latest('published_at');
    }

    /**
     * Scope for breaking posts regardless of expiration.
     *
     * @param  Builder $query
     * @return Builder
     */
    protected function scopeBreaking(Builder $query): Builder
    {
        return $query->where('is_breaking', true);
    }
}
