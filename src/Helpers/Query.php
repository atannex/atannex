<?php

namespace Atannex\Helpers;

use App\Models\Pages\Page;
use Illuminate\Database\Eloquent\Relations\HasMany;


trait Query
{
    /**
     * Ensure pagination/take limits are always positive integers.
     */
    private function sanitizeLimit(int $limit): int
    {
        return max(1, $limit);
    }
    /* -----------------------------------------------------------------
     |  Private query helpers
     | -----------------------------------------------------------------
     */

    /**
     * Common active page query builder.
     */
    private function activePageQuery()
    {
        return Page::query()->active();
    }

    /**
     * Scope callback for published children categories.
     */
    private function publishedChildren(): \Closure
    {
        return fn(HasMany $query) => $query->published(false);
    }

    /**
     * Scope callback for active sections + widgets.
     */
    private function activeSections(): \Closure
    {
        return fn($query) => $query
            ->wherePivot('is_active', true)
            ->with(['widgets' => $this->activeWidgets()]);
    }

    /**
     * Scope callback for active widgets.
     */
    private function activeWidgets(): \Closure
    {
        return fn($query) => $query->wherePivot('is_active', true);
    }
}
