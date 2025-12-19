<?php

namespace Atannex\Interactions\Components;

use App\Events\ModelViewed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * Trait CanView
 *
 * Adds view-tracking for Livewire components with DB-only throttling.
 * Requires the model to use the HasViews trait.
 */
trait CanView
{
    /**
     * Total number of views for the model.
     */
    public int $viewsCount = 0;

    /**
     * Record a view for the current viewer with DB throttling.
     *
     * @param int $ttlMinutes Minutes before another view from the same viewer is accepted.
     */
    protected function recordView(int $ttlMinutes = 10): void
    {
        $viewerId = Auth::id();
        $ip       = Request::ip();

        $recentViewExists = $this->post->views()
            ->where(function ($query) use ($viewerId, $ip) {
                if ($viewerId !== null) {
                    $query->where('user_id', $viewerId);
                } else {
                    $query->whereNull('user_id')
                        ->where('ip_address', $ip);
                }
            })
            ->where('viewed_at', '>=', now()->subMinutes($ttlMinutes))
            ->exists();

        if (! $recentViewExists) {
            $this->post->recordView();
            $this->fireViewAnalyticsEvent();
        }

        $this->syncViewState();
    }

    /**
     * Sync the Livewire component state with the model view count.
     */
    protected function syncViewState(): void
    {
        $this->viewsCount = $this->post->viewsCount();
    }

    /**
     * Dispatch analytics event after a successful view record.
     */
    protected function fireViewAnalyticsEvent(): void
    {
        event(new ModelViewed($this->post, Request::ip()));
    }
}
