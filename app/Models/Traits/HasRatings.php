<?php

declare(strict_types=1);

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Comments\Rating;

trait HasRatings
{
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function ratings(): MorphMany
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    protected function currentVisitorRelation(): MorphMany
    {
        return $this->ratings()
            ->where('visitor_key', $this->resolveVisitorKey());
    }

    /*
    |--------------------------------------------------------------------------
    | State Checks
    |--------------------------------------------------------------------------
    */

    public function userRating(): ?int
    {
        return $this->currentVisitorRelation()->value('rating');
    }

    public function hasUserRated(): bool
    {
        return $this->currentVisitorRelation()->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Core Actions
    |--------------------------------------------------------------------------
    */

    public function rate(int $value, ?string $comment = null): void
    {
        if (!$this->isValidRating($value)) {
            return;
        }

        $this->ratings()->updateOrCreate(
            [
                'visitor_key' => $this->resolveVisitorKey(),
            ],
            [
                'user_id'    => Auth::id(),
                'session_id' => request()->session()->getId(),
                'ip_address' => request()->ip(),
                'rating'     => $value,
                'comment'    => $comment,
            ]
        );
    }

    public function toggleRating(int $value): void
    {
        $current = $this->userRating();

        if ($current === null) {
            $this->rate($value);
            return;
        }

        $current === $value
            ? $this->unrate()
            : $this->rate($value);
    }

    public function unrate(): void
    {
        $this->currentVisitorRelation()->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | Aggregates
    |--------------------------------------------------------------------------
    */

    public function ratingCount(): int
    {
        return $this->ratings()->count();
    }

    public function averageRating(): float
    {
        return round(
            (float) $this->ratings()->avg('rating'),
            2
        );
    }

    public function ratingBreakdown(): array
    {
        return $this->ratings()
            ->selectRaw('rating, COUNT(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating')
            ->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    protected function isValidRating(int $value): bool
    {
        return $value >= 1 && $value <= 5;
    }
}
