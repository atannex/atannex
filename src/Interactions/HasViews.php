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
 * Cache-free view tracking for polymorphic models.
 * Supports authenticated users and guests using DB-level guarantees.
 */
trait HasViews
{
    public function views(): MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }

    protected function viewerIdentity(): array
    {
        return [
            'user_id'    => Auth::id(),
            'ip_address' => Request::ip(),
        ];
    }

    protected function viewerQuery(): Builder
    {
        return $this->views()
            ->where('user_id', Auth::id())
            ->where('ip_address', Request::ip());
    }

    /**
     * Record a view.
     * Re-visits update timestamp instead of creating new rows.
     */
    public function recordView(): void
    {
        $this->views()->updateOrCreate(
            $this->viewerIdentity(),
            [
                'user_agent' => Request::userAgent(),
                'viewed_at'  => now(),
            ]
        );
    }

    /**
     * Total views count.
     */
    public function viewsCount(): int
    {
        return $this->views()->count();
    }

    /**
     * Unique views count.
     */
    public function uniqueViewsCount(): int
    {
        return $this->views()
            ->selectRaw('COUNT(DISTINCT COALESCE(user_id, ip_address)) as count')
            ->value('count');
    }

    /**
     * Views within a time window (optional).
     */
    public function viewsSince(\DateTimeInterface $since): int
    {
        return $this->views()
            ->where('viewed_at', '>=', $since)
            ->count();
    }
}
