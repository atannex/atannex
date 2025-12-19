<?php

namespace Atannex\Interactions;

use App\Models\Interactions\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * Trait HasViews
 *
 * Cache-free, DB-driven view tracking for polymorphic models.
 * Supports guests and authenticated users with identity merging.
 */
trait HasViews
{
    public function views(): MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }

    /**
     * Upgrade an existing guest view to an authenticated user view.
     * Prevents double counting when a guest logs in.
     */
    public function mergeGuestViewIntoUser(): void
    {
        if (! Auth::check()) {
            return;
        }

        $this->views()
            ->whereNull('user_id')
            ->where('ip_address', Request::ip())
            ->update([
                'user_id'   => Auth::id(),
                'viewed_at' => now(),
            ]);
    }

    /**
     * Build the identity used for creating/updating a view.
     */
    protected function viewerIdentity(): array
    {
        if (Auth::check()) {
            return [
                'user_id'    => Auth::id(),
                'ip_address' => Request::ip(),
            ];
        }

        return [
            'user_id'    => null,
            'ip_address' => Request::ip(),
        ];
    }

    /**
     * Record a view.
     * Updates timestamps instead of creating duplicate rows.
     */
    public function recordView(): void
    {
        // Ensure guest views are merged before recording
        $this->mergeGuestViewIntoUser();

        $this->views()->updateOrCreate(
            $this->viewerIdentity(),
            [
                'user_agent' => Request::userAgent(),
                'viewed_at'  => now(),
            ]
        );
    }

    /**
     * Total views count (unique viewers).
     */
    public function viewsCount(): int
    {
        return $this->views()->count();
    }

    /**
     * Unique views count (explicit, analytics-safe).
     */
    public function uniqueViewsCount(): int
    {
        return $this->views()
            ->selectRaw('COUNT(DISTINCT COALESCE(user_id, ip_address)) as count')
            ->value('count');
    }

    /**
     * Views within a time window.
     */
    public function viewsSince(\DateTimeInterface $since): int
    {
        return $this->views()
            ->where('viewed_at', '>=', $since)
            ->count();
    }
}
