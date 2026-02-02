<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\Comments\Rateable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Builder;

trait HasRateable
{
    public function ratings(): MorphMany
    {
        return $this->morphMany(Rateable::class, 'rateable');
    }

    public function ratingCount(): int
    {
        return $this->ratings()->count();
    }

    public function averageRating(): float
    {
        $avg = $this->ratings()->avg('rating');
        return $avg !== null ? round((float) $avg, 2) : 0.0;
    }

    public function hasRatings(): bool
    {
        return $this->ratings()->exists();
    }

    public function ratingByUser(?int $userId = null): ?Rateable
    {
        $userId ??= Auth::id();

        if (!$userId) {
            return null;
        }

        return $this->ratings()
            ->where('user_id', $userId)
            ->latest()
            ->first();
    }

    public function hasUserRated(?int $userId = null): bool
    {
        return (bool) $this->ratingByUser($userId);
    }

    public function addRating(
        int $rating,
        ?string $comment = null,
        ?int $userId = null,
        ?string $ipAddress = null
    ): Rateable {
        $userId ??= Auth::id();

        return $this->ratings()->updateOrCreate(
            [
                'rating_hash' => Rateable::generateRatingHash(
                    $userId,
                    static::class,
                    $this->getKey()
                ),
            ],
            [
                'user_id'    => $userId,
                'rating'     => $rating,
                'comment'    => $comment,
                'ip_address' => $ipAddress,
            ]
        );
    }

    public function removeRating(?int $userId = null): bool
    {
        $userId ??= Auth::id();

        if (!$userId) {
            return false;
        }

        return (bool) $this->ratings()
            ->where('user_id', $userId)
            ->delete();
    }

    public function ratingsBreakdown(): array
    {
        return $this->ratings()
            ->selectRaw('rating, COUNT(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating')
            ->toArray();
    }

    public function scopeWithAverageRating(Builder $query): Builder
    {
        return $query->withAvg('ratings', 'rating');
    }

    public function scopeWithRatingCount(Builder $query): Builder
    {
        return $query->withCount('ratings');
    }
}
