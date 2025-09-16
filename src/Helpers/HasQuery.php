<?php

namespace Atannex\Helpers;

use Closure;
use App\Models\Pages\Page;
use App\Models\Posts\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasQuery
{
    /**
     * Default eager-load relations for posts.
     */
    private function defaultRelations(): array
    {
        return ['category', 'tags', 'author'];
    }

    /**
     * Closure for eager-loading authors with post counts.
     */
    private function authorWithPostCount(): Closure
    {
        return fn($query) => $query->withCount('posts');
    }

    /**
     * Empty post query builder (always returns no results).
     */
    private function emptyPostQuery(): Builder
    {
        return Post::whereRaw('1 = 0')
            ->published()
            ->with($this->defaultRelations());
    }

    /**
     * Empty paginator for safe return when no results.
     */
    private function emptyPaginator(int $limit): LengthAwarePaginator
    {
        return new LengthAwarePaginator(collect(), 0, $this->sanitizeLimit($limit));
    }

    /**
     * Ensure pagination/take limits are always positive integers.
     */
    private function sanitizeLimit(int $limit): int
    {
        return max(1, $limit);
    }

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
    private function publishedChildren(): Closure
    {
        return fn(HasMany $query) => $query->published(false);
    }

    /**
     * Scope callback for active sections + widgets.
     */
    private function activeSections(): Closure
    {
        return fn($query) => $query
            ->wherePivot('is_active', true)
            ->with(['widgets' => $this->activeWidgets()]);
    }

    /**
     * Scope callback for active widgets.
     */
    private function activeWidgets(): Closure
    {
        return fn($query) => $query->wherePivot('is_active', true);
    }

    /**
     * Parse year/month from a slug string.
     */
    protected function parseDateSlug(string $slug): array
    {
        [$year, $month] = array_pad(explode('/', $slug, 2), 2, null);

        return ['year' => $year, 'month' => $month];
    }
}
