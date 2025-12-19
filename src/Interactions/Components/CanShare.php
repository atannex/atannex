<?php

namespace Atannex\Interactions\Components;

/**
 * Trait CanShare
 *
 * Exposes share count state for Livewire components.
 * Requires the model to implement sharesCount().
 */
trait CanShare
{
    /**
     * Total number of shares.
     */
    public int $sharesCount = 0;

    /**
     * Initialize share-related state.
     */
    public function mountCanShare(): void
    {
        $this->syncShareState();
    }

    /**
     * Sync the share count from the model.
     */
    protected function syncShareState(): void
    {
        $this->sharesCount = $this->post->sharesCount();
    }
}
