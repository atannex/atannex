<?php

namespace Atannex\Traits;

use Carbon\Carbon;
use App\Enums\Flag;
use Illuminate\Database\Eloquent\Builder;

trait HasBreaking
{
    /**
     * Scope for active breaking posts.
     *
     * Uses the `breaking_until` column to determine if a post is still breaking.
     * Supports dynamic timezone and optional minimum priority.
     *
     * @param Builder $query
     * @param string $timezone User timezone (default: app timezone)
     * @return Builder
     */
    public function scopeActiveBreaking(Builder $query, ?string $timezone): Builder
    {
        $now = Carbon::now($timezone ?? config('app.timezone'));

        $query->where('flag', Flag::PUBLISHED)
            ->where('breaking_until', '>=', $now)
            ->whereColumn('breaking_until', '>', 'published_at');

        return $query->where('is_breaking', true)->orderBy('published_at', 'desc');
    }
}
