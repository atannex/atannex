<?php

namespace Atannex\Traits;

use Carbon\Carbon;
use App\Enums\Flag;
use App\Events\Posts\BreakingPost;
use Illuminate\Database\Eloquent\Builder;

trait HasBreaking
{
    /**
     * Scope for active breaking posts.
     *
     * Usage: Post::activeBreaking()->get();
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActiveBreaking(Builder $query): Builder
    {
        return $query->where('is_breaking', true)
            ->where('breaking_until', '>', Carbon::now())
            ->where('flag', Flag::PUBLISHED);
    }

    /**
     * Extend the breaking status for additional hours.
     *
     * @param int $hours
     * @return bool
     */
    public function extendBreaking(int $hours = 1): bool
    {
        $hours = max(1, $hours);

        $updated = $this->update([
            'is_breaking'   => true,
            'breaking_until' => Carbon::now()->addHours($hours),
        ]);

        if ($updated) {
            event(new BreakingPost($this));
        }

        return $updated;
    }

    /**
     * Expire the breaking status immediately.
     *
     * @return bool
     */
    public function expireBreaking(): bool
    {
        $updated = $this->update([
            'is_breaking'    => false,
            'breaking_until' => null,
        ]);

        if ($updated) {
            event(new BreakingPost($this));
        }

        return $updated;
    }

    /**
     * Check if the post is currently breaking news.
     *
     * Access via: $post->is_currently_breaking
     *
     * @return bool
     */
    public function getIsCurrentlyBreakingAttribute(): bool
    {
        return $this->is_breaking && $this->breaking_until?->gt(Carbon::now());
    }

    /**
     * Automatically handle breaking status transition.
     *
     * Extends breaking news if active, expires if past breaking_until.
     *
     * @param int $extendHours Number of hours to extend if already breaking
     * @return bool
     */
    public function handleBreakingTransition(int $extendHours = 1): bool
    {
        if ($this->is_breaking && $this->breaking_until?->isPast()) {
            return $this->expireBreaking();
        }

        return $this->extendBreaking($extendHours);
    }
}
