<?php

namespace Atannex\Interactions\Components;

/**
 * Trait CanRate
 *
 * Provides rating functionality for Livewire components.
 * Assumes the component has a `$post` property using the HasRatings trait.
 */
trait CanRate
{
    /**
     * Indicates whether the user has rated the model.
     */
    public bool $hasRating = false;

    /**
     * Total number of ratings for the model.
     */
    public int $ratingCount = 0;

    /**
     * Average rating value for the model.
     */
    public float $averageRating = 0.0;

    /**
     * Current user's rating value for the model.
     */
    public ?int $userRating = null;

    /**
     * Handle user rating input.
     * If the same rating is selected again, it will remove the rating.
     *
     * @param  int  $value  Rating value (1 to 5).
     */
    public function rate(int $value): void
    {
        if ($this->userRating === $value) {
            $this->post->unrate();
        } else {
            $this->post->rate($value);
        }

        $this->syncRatingState();
    }

    /**
     * Sync the component's rating state with the model's rating data.
     */
    protected function syncRatingState(): void
    {
        $this->ratingCount = $this->post->ratingCount();
        $this->averageRating = $this->post->averageRating() ?? 0.0;
        $this->userRating = $this->post->userRating();

        $this->hasRating = $this->userRating !== null;
    }
}
