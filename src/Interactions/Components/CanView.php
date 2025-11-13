<?php

namespace Atannex\Interactions\Components;

use App\Events\ModelViewed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * Trait CanView
 *
 * Adds view-tracking for Livewire components with throttling using the database.
 * Requires the model to use the HasViews trait and have a `views` table with IP/user tracking.
 */
trait CanView
{
    /**
     * Total number of views for the model.
     */
    public int $viewsCount = 0;

    /**
     * Record a view for the current user/IP, with throttling via database.
     *
     * @param  int  $ttlMinutes  Minutes before another view from the same IP/user is accepted.
     */
    protected function recordView(int $ttlMinutes = 10): void
    {
        $recentViewExists = $this->post->views()
            ->where('user_id', Auth::id())
            ->orWhere('ip_address', Request::ip())
            ->where('viewed_at', '>=', now()->subMinutes($ttlMinutes))
            ->exists();

        if (! $recentViewExists) {
            $this->post->recordView();
            $this->fireViewAnalyticsEvent();
        }

        $this->syncViewState();
    }

    /**
     * Sync the component view count with the model's current views.
     */
    protected function syncViewState(): void
    {
        $this->viewsCount = $this->post->viewsCount();
    }

    /**
     * Trigger analytics logic or event dispatching after a view is recorded.
     */
    protected function fireViewAnalyticsEvent(): void
    {
        event(new ModelViewed($this->post, Request::ip()));
    }
}
