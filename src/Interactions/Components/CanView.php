<?php

namespace Atannex\Interactions\Components;

use App\Events\ModelViewed;

/**
 * Trait CanView
 *
 * Provides unique view tracking and exposes view count
 * for Livewire components.
 */
trait CanView
{
    /**
     * Total number of unique views.
     */
    public int $viewsCount = 0;

    /**
     * Record a unique view for the current visitor.
     */
    protected function recordView(): void
    {
        $this->post->recordView();

        event(new ModelViewed($this->post));
    }

    /**
     * Sync the view count from the database.
     */
    protected function syncViewState(): void
    {
        $this->viewsCount = $this->post->viewsCount();
    }
}
