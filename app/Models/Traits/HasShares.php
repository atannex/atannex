<?php

declare(strict_types=1);

namespace App\Models\Traits;

use Illuminate\Support\Str;
use App\Models\Comments\Share;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasShares
{
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function shares(): MorphMany
    {
        return $this->morphMany(Share::class, 'shareable');
    }

    protected function currentVisitorShare(string $platform): MorphMany
    {
        return $this->shares()
            ->where('visitor_key', $this->resolveVisitorKey())
            ->where('platform', $platform);
    }

    /*
    |--------------------------------------------------------------------------
    | Core Actions
    |--------------------------------------------------------------------------
    */

    public function recordShare(string $platform): Share
    {
        $visitorKey = $this->resolveVisitorKey();

        $share = $this->shares()->firstOrNew([
            'visitor_key' => $visitorKey,
            'platform'    => $platform,
        ]);

        if ($share->exists) {
            $share->increment('share_count');
        } else {
            $share->fill([
                'visitor_key' => $visitorKey,
                'platform'    => $platform,
                'share_count' => 1,
                'session_id'  => Request::session()->getId(),
                'ip_address'  => Request::ip(),
                'user_agent'  => Request::userAgent(),
            ])->save();
        }

        return $share;
    }

    public function hasShared(string $platform): bool
    {
        return $this->currentVisitorShare($platform)->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Aggregates
    |--------------------------------------------------------------------------
    */

    /**
     * Total share clicks (all visitors, all platforms)
     */
    public function totalShareClicks(): int
    {
        return (int) $this->shares()->sum('share_count');
    }

    /**
     * Unique sharers (regardless of platform)
     */
    public function uniqueSharersCount(): int
    {
        return (int) $this->shares()
            ->distinct('visitor_key')
            ->count('visitor_key');
    }

    /**
     * Total share clicks per platform
     */
    public function shareClicksByPlatform(string $platform): int
    {
        return (int) $this->shares()
            ->where('platform', $platform)
            ->sum('share_count');
    }

    /**
     * Platform breakdown (click-based)
     */
    public function shareBreakdown(): array
    {
        return $this->shares()
            ->selectRaw('platform, SUM(share_count) as total')
            ->groupBy('platform')
            ->pluck('total', 'platform')
            ->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeWithSharesCount($query)
    {
        return $query->withCount('shares');
    }

    /*
    |--------------------------------------------------------------------------
    | Visitor Identity
    |--------------------------------------------------------------------------
    */

    protected function resolveVisitorKey(): string
    {
        $visitorId = Request::cookie('visitor_id');

        if (! $visitorId) {
            $visitorId = (string) Str::uuid();

            cookie()->queue(
                cookie('visitor_id', $visitorId, 60 * 24 * 365)
            );
        }

        return 'guest_' . $visitorId;
    }
}
