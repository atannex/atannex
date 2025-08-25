<?php

namespace App\Livewire\Interactions;

use Illuminate\Support\Carbon;
use App\Models\Interactions\Rating;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait HasRatings
 *
 * Provides functionality for models to handle rating/unrating interactions using soft deletes.
 *
 * @package App\Livewire\Traits
 */
trait HasRatings
{
    /**
     * Get the ratings associated with the model, including soft-deleted ones.
     */
    public function ratings(): MorphMany
    {
        return $this->morphMany(Rating::class, 'rateable')->withTrashed();
    }

    /**
     * Add or restore a rating for the authenticated user.
     *
     * @param int $value The rating value (1–5).
     * @return bool Returns true if the rating was created/restored, false otherwise.
     */
    public function rate(int $value): bool
    {
        if (!Auth::check() || $value < 1 || $value > 5) {
            return false;
        }

        try {
            $rating = $this->ratings()
                ->withTrashed()
                ->where('user_id', Auth::id())
                ->first();

            if ($rating && $rating->trashed()) {
                $rating->restore();
                $rating->update([
                    'rating' => $value,
                    'rated_at' => now(),
                ]);
                return true;
            }

            $this->ratings()->updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'rateable_id' => $this->id,
                    'rateable_type' => get_class($this),
                ],
                [
                    'rating' => $value,
                    'rated_at' => now(),
                ]
            );

            return true;
        } catch (QueryException $e) {
            Log::error('Rating error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Remove a rating (soft delete) for the authenticated user.
     *
     * @return bool Returns true if the rating was soft-deleted, false otherwise.
     */
    public function unrate(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $rating = $this->ratings()
            ->where('user_id', Auth::id())
            ->first();

        if ($rating && !$rating->trashed()) {
            $rating->delete();
            return true;
        }

        return false;
    }

    /**
     * Get the average rating for the model (active ratings only).
     */
    public function averageRating(): ?float
    {
        return $this->ratings()->whereNull('deleted_at')->avg('rating') ?: null;
    }

    /**
     * Get the total number of active ratings for the model.
     */
    public function ratingCount(): int
    {
        return $this->ratings()->whereNull('deleted_at')->count();
    }

    /**
     * Get the rating given by the authenticated user (active rating only).
     */
    public function userRating(): ?int
    {
        if (!Auth::check()) {
            return null;
        }

        return $this->ratings()
            ->where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->value('rating');
    }

    /**
     * Check if the authenticated user has rated the model (active rating only).
     */
    public function isRatedByUser(): bool
    {
        return Auth::check() && $this->ratings()
            ->where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->exists();
    }

    /**
     * Get the timestamp when the authenticated user rated the model.
     */
    public function ratedAt(): ?Carbon
    {
        if (!Auth::check()) {
            return null;
        }

        return $this->ratings()
            ->where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->value('rated_at');
    }
}
