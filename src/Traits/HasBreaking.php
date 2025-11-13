<?php

namespace Atannex\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Date;

trait HasBreaking
{
    /**
     * Scope for active breaking posts.
     *
     * Uses the `breaking_until` column to determine if a post is still breaking.
     * Ensures breaking_until is greater than published_at and not expired.
     *
     * @param  string|null  $timezone  User timezone (default: app timezone)
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
}
