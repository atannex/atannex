<?php

namespace Atannex\Interactions\Components;

/**
 * Trait CanLike
 *
 * Provides like/unlike functionality for Livewire components.
 * Relies on the parent component having a `$post` property that uses the HasLikes trait.
 */
trait CanLike
{
    /**
     * Indicates whether the current user has liked the model.
     *
     * @var bool
     */
    public bool $isLiked = false;

    /**
     * Total number of likes for the model.
     *
     * @var int
     */
    public int $likesCount = 0;

    /**
     * Initialize the like state on component mount.
     *
     * @return void
     */
    public function mountCanLike(): void
    {
        $this->syncLikeState();
    }

    /**
     * Handle a like action and update the UI state.
     *
     * @return void
     */
    public function like(): void
    {
        $this->post->like();
        $this->syncLikeState();
    }

    /**
     * Handle an unlike action and update the UI state.
     *
     * @return void
     */
    public function unlike(): void
    {
        $this->post->unlike();
        $this->syncLikeState();
    }

    /**
     * Sync the component's state with the model's like status and count.
     *
     * @return void
     */
    protected function syncLikeState(): void
    {
        $this->isLiked = $this->post->isLikedByUser();
        $this->likesCount = $this->post->likesCount();
    }
}
