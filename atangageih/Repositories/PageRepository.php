<?php

namespace Atangageih\Repositories;

use App\Models\Pages\Page;
use App\Models\Pages\Category;
use Illuminate\Support\Collection;
use Atangageih\Contracts\PageInterface;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PageRepository implements PageInterface
{
    /**
     * Get all top-level published page categories with their children recursively loaded.
     *
     * @return Collection
     */
    public function getAllCategoryPages(): Collection
    {
        return Category::query()
            ->published()
            ->whereNull('parent_id')
            ->with([
                'children' => fn(HasMany $query) => $query->published()
            ])
            ->get();
    }

    /**
     * Get all published and active home pages.
     *
     * @return Collection
     */
    public function getAllHomePages(): Collection
    {
        return Page::active()->get();
    }

    /**
     * Get a single active home page by slug with active sections and widgets.
     *
     * @param string $slug
     * @return Page|null
     */
    public function getHomePage(string $slug): ?Page
    {
        return Page::query()
            ->where('slug', $slug)
            ->active()
            ->with([
                'sections' => function ($query) {
                    $query->wherePivot('is_active', true)
                        ->with(['widgets' => fn($widgetQuery) => $widgetQuery->wherePivot('is_active', true)]);
                },
            ])
            ->firstOrFail();
    }
}
