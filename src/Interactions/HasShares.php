<?php

// app/Traits/HasShares.php or Atannex\Interactions\HasShares.php

declare(strict_types=1);

namespace Atannex\Interactions;

use App\Models\Interactions\Share;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Trait HasShares
 *
 * Adds share-tracking functionality to Eloquent models.
 * Tracks per-user, per-platform share counts.
 *
 * @mixin Model
 */
trait HasShares
{
    public function shares(): MorphMany
    {
        return $this->morphMany(Share::class, 'shareable');
    }

    protected function userShareQuery(string $platform): Builder
    {
        return $this->shares()
            ->where('user_id', $this->getAuthUserId())
            ->where('platform', $platform);
    }

    /**
     * Record a share (only used if you later enable sharing).
     */
    public function share(string $platform): void
    {
        $userId = $this->getAuthUserId();

        $this->shares()->updateOrCreate(
            ['user_id' => $userId, 'platform' => $platform],
            [
                'share_count' => DB::raw('COALESCE(share_count, 0) + 1'),
                'shared_at'   => now(),
            ]
        );
    }

    public function sharesCount(): int
    {
        if ($this->relationLoaded('shares')) {
            return (int) $this->shares->sum('share_count');
        }

        return (int) $this->shares()->sum('share_count');
    }

    protected function getAuthUserId(): int
    {
        $user = Auth::user();
        return (int) $user->getAuthIdentifier();
    }

    public function sharesCountFor(string $platform): int
    {
        return (int) $this->shares()->where('platform', $platform)->sum('share_count');
    }

    public function hasSharedOn(string $platform): bool
    {
        return Auth::check() && $this->userShareQuery($platform)->exists();
    }
}
