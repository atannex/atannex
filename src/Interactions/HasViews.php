<?php

namespace Atannex\Interactions;

use App\Models\Interactions\View;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * Trait HasViews
 *
 * DB-driven unique view tracking using visitor_id.
 */
trait HasViews
{
    public function views(): MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }

    /**
     * Record a unique view for the current visitor.
     */
    public function recordView(): void
    {
        $visitorId = app('visitor_id');

        $this->views()->updateOrCreate(
            [
                'visitor_id'    => $visitorId,
                'viewable_id'   => $this->getKey(),
                'viewable_type' => $this->getMorphClass(),
            ],
            [
                'user_id'    => Auth::id(),
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'viewed_at'  => now(),
            ]
        );
    }

    /**
     * Total unique views (people).
     */
    public function viewsCount(): int
    {
        return $this->views()->count();
    }
}
