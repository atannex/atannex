<?php

declare(strict_types=1);

namespace App\Models\Traits;

use Illuminate\Support\Str;
use App\Models\Comments\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasViews
{
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function views(): MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }

    protected function currentVisitorView(): MorphMany
    {
        return $this->views()
            ->where('visitor_key', $this->resolveVisitorKey());
    }

    /*
    |--------------------------------------------------------------------------
    | Core Actions
    |--------------------------------------------------------------------------
    */

    /**
     * Record a unique view (only once per visitor).
     */
    public function addView(): void
    {
        if ($this->isBot()) {
            return;
        }

        $this->views()->firstOrCreate(
            [
                'visitor_key' => $this->resolveVisitorKey(),
            ],
            [
                'session_id' => request()->session()->getId(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]
        );
    }

    protected function isBot(): bool
    {
        $agent = strtolower(request()->userAgent() ?? '');

        return str_contains($agent, 'bot')
            || str_contains($agent, 'crawl')
            || str_contains($agent, 'spider');
    }


    /**
     * Check if current visitor has already viewed.
     */
    public function hasViewed(): bool
    {
        return $this->currentVisitorView()->exists();
    }

    /**
     * Remove current visitor view.
     */
    public function removeView(): void
    {
        $this->currentVisitorView()->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | Aggregates
    |--------------------------------------------------------------------------
    */

    /**
     * Total unique views.
     * (Each row = one unique viewer)
     */
    public function viewCount(): int
    {
        return $this->views()->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeWithViewsCount($query)
    {
        return $query->withCount('views');
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
