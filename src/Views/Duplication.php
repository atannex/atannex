<?php

namespace Atannex\Views;

use App\Models\Tags\Tag;
use Illuminate\View\View;
use Illuminate\Support\Collection;

trait Duplication
{
    /**
     * Shared rendering logic for views that follow the same pattern:
     * - Have posts
     * - May have a first tag for related/popular content
     */
    protected function sharedRender(string $view, array $data, string $seoTitle): View
    {
        $data['seoTitle'] = $seoTitle;
        return view($view, $data);
    }

    /**
     * Wrapper to avoid repeating null checks for firstTag
     */
    protected function popularTags(?Tag $firstTag, int $limit = 8): Collection
    {
        return $firstTag
            ? $this->categoryService->popularTags($firstTag, $limit)
            : collect();
    }

    /**
     * Wrapper for related categories by tag (with fallback)
     */
    protected function relatedCategories(?Tag $firstTag): Collection
    {
        return $firstTag
            ? $this->categoryService->relatedCategoriesByTag($firstTag)
            : collect();
    }

    /**
     * Legacy alias for backward compatibility if needed
     */
    protected function renderView(string $view, array $data = [], string $seoTitle = ''): View
    {
        return $this->sharedRender($view, $data, $seoTitle);
    }
}
