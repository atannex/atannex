<?php

declare(strict_types=1);

namespace Atannex\Interactions;

use App\Models\Interactions\Share;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

/**
 * Trait HasShares
 *
 * Adds event-based share tracking to Eloquent models.
 * Each share action creates a single row.
 *
 * @mixin Model
 */
trait HasShares
{
    /**
     * Polymorphic relation to share events.
     */
    public function shares(): MorphMany
    {
        return $this->morphMany(Share::class, 'shareable');
    }

    /**
     * Record a share event.
     * One call = one share.
     */
    public function share(string $platform): void
    {
        $this->shares()->create([
            'visitor_id' => session('visitor_id'),
            'user_id'    => Auth::id(),
            'platform'   => $platform,
            'shared_at'  => now(),
        ]);
    }

    /**
     * Total number of shares for this model.
     */
    public function sharesCount(): int
    {
        if ($this->relationLoaded('shares')) {
            return $this->shares->count();
        }

        return $this->shares()->count();
    }
}
