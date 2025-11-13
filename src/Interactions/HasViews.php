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
 * Adds view-tracking functionality to models using polymorphic relations.
 * Tracks authenticated user views with IP and timestamp.
 */
trait HasViews
{
    /**
     * Define a polymorphic one-to-many relationship with the View model.
     */
    public function views(): MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }

    /**
     * Query builder for retrieving the current user's view record.
     *
     * @return Builder
     */
    protected function userViewQuery()
    {
        return $this->views()->where('user_id', Auth::id());
    }

    /**
     * Record a view by the currently authenticated user.
     * Updates the existing view if it exists; otherwise, creates a new one.
     */
    public function recordView(): void
    {
        $existing = $this->userViewQuery()->first();

        if ($existing) {
            $existing->update([
                'ip_address' => Request::ip(),
                'viewed_at' => now(),
            ]);
        } else {
            $this->views()->create([
                'user_id' => Auth::id(),
                'ip_address' => Request::ip(),
                'viewed_at' => now(),
            ]);
        }
    }

    /**
     * Get the total number of views for the current model.
     */
    public function viewsCount(): int
    {
        return $this->views()->count();
    }
}
