<?php

namespace App\Livewire\Interactions;

use App\Models\Interactions\View;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * Trait HasViews
 *
 * Provides functionality for models to handle view interactions, including recording, counting, and retrieving view timestamps.
 *
 * @package App\Livewire\Traits
 */
trait HasViews
{
    /**
     * Get the views associated with the model.
     */
    public function views(): MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }

    /**
     * Record a view for the model by the authenticated user.
     *
     * @return bool Returns true if the view was recorded or already exists, false if the user is not authenticated.
     */
    public function recordView(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $this->views()->firstOrCreate(
            ['user_id' => Auth::id()],
            [
                'ip_address' => Request::ip(),
                'viewed_at' => now(),
            ]
        );

        return true;
    }

    /**
     * Get the total number of views for the model.
     */
    public function viewsCount(): int
    {
        return $this->views()->count();
    }

    /**
     * Get the timestamp when the authenticated user viewed the model.
     *
     * @return Carbon|null The viewed_at timestamp or null if not viewed or user is not authenticated.
     */
    public function viewedAt(): ?Carbon
    {
        if (!Auth::check()) {
            return null;
        }

        return $this->views()->where('user_id', Auth::id())->value('viewed_at');
    }
}
