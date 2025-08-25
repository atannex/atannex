<?php

namespace App\Livewire\Interactions;

use App\Models\Interactions\Share;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Trait Sharable
 *
 * Provides functionality for models to handle share interactions, including creating, counting, and retrieving share timestamps.
 *
 * @package App\Livewire\Traits
 */
trait HasShares
{
    /**
     * Get the shares associated with the model.
     */
    public function shares(): MorphMany
    {
        return $this->morphMany(Share::class, 'shareable');
    }

    /**
     * Get the total number of shares for the model.
     */
    public function sharesCount(): int
    {
        return $this->shares()->sum('share_count');
    }

    /**
     * Get the most recent share timestamp for the authenticated user, optionally filtered by platform.
     *
     * @param string|null $platform The platform to filter by (e.g., 'twitter', 'facebook'). If null, returns the latest across all platforms.
     * @return Carbon|null The most recent shared_at timestamp or null if not shared or user is not authenticated.
     */
    public function lastSharedAt(?string $platform = null): ?Carbon
    {
        if (!Auth::check()) {
            return null;
        }

        $query = $this->shares()->where('user_id', Auth::id());

        if ($platform !== null && !empty(trim($platform))) {
            $query->where('platform', $platform);
        }

        return $query->orderByDesc('shared_at')->value('shared_at');
    }
}
