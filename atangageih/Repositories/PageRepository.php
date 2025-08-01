<?php

namespace Atangageih\Repositories;

use App\Models\Pages\Page;
use App\Models\Pages\Category;
use Illuminate\Support\Collection;
use Atangageih\Contracts\PageInterface;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class PageRepository implements PageInterface
{
    /**
     * Retrieve all top-level published page categories with their children recursively loaded.
     *
     * @return Collection<int, Category>
     */
    public function getAllCategoryPages(): Collection
    {
        return Category::query()
            ->published()
            ->whereNull('parent_id')
            ->with([
                'children' => fn (HasMany $query) => $query->published(),
            ])
            ->get();
    }

    /**
     * Retrieve all published and active home pages.
     *
     * @return Collection<int, Page>
     */
    public function getAllHomePages(): Collection
    {
        return Page::query()
            ->active()
            ->get();
    }

    /**
     * Retrieve a single active home page by slug, including only active sections and widgets.
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
                'sections' => function (Builder $query) {
                    $query->wherePivot('is_active', true)
                        ->with([
                            'widgets' => fn (Builder $widgetQuery) => $widgetQuery->wherePivot('is_active', true),
                        ]);
                },
            ])
            ->first();
    }
}
