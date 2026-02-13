<?php

namespace App\Livewire\Concerns;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\RateLimiter;

trait CanRate
{
    public int $ratingCount = 0;
    public float $averageRating = 0.0;

    public ?int $myRating = 0;

    protected int $cooldown = 10;

    /*
    |--------------------------------------------------------------------------
    | Public API
    |--------------------------------------------------------------------------
    */

    public function handleRate(int $value): void
    {
        if (! $this->canRate()) {
            return;
        }

        $this->getRateableModel()->toggleRating($value);

        $this->syncRatingState();
    }

    /*
    |--------------------------------------------------------------------------
    | State Sync
    |--------------------------------------------------------------------------
    */

    protected function syncRatingState(): void
    {
        $model = $this->getRateableModel();

        $this->ratingCount   = $model->ratingCount();
        $this->averageRating = $model->averageRating();
        $this->myRating      = $model->userRating();
    }

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */

    protected function canRate(): bool
    {
        return RateLimiter::attempt(
            $this->rateLimitKey(),
            1,
            fn() => true,
            $this->cooldown
        );
    }

    protected function rateLimitKey(): string
    {
        return sprintf(
            '%s:%s:%s',
            $this->getRateLimitPrefix(),
            $this->getRateableModel()->getKey(),
            Request::ip()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Abstract Methods (Must Be Implemented)
    |--------------------------------------------------------------------------
    */

    abstract protected function getRateableModel();

    protected function getRateLimitPrefix(): string
    {
        return 'rating';
    }
}
