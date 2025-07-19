<?php

namespace Atangageih\Repositories;

use App\Models\Pages\Page;
use App\Models\Pages\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
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
     * Get all published and active home pages, cached for 1 hour.
     *
     * @return Collection
     */
    public function getAllHomePages(): Collection
    {
        return Cache::remember('page_navigation_items', 3600, function () {
            return Page::active()->get();
        });
    }
    public function getHomePage(string $slug): ?Page
    {
        return Cache::remember("page_{$slug}", 3600, function () use ($slug) {
            return Page::query()
                ->where('slug', $slug)
                ->active()
                ->with([
                    'sections' => function ($query) {
                        $query
                            ->wherePivot('is_active', true)
                            ->with([
                                'widgets' => function ($widgetQuery) {
                                    $widgetQuery
                                        ->wherePivot('is_active', true);
                                }
                            ]);
                    },
                ])
                ->firstOrFail();
        });
    }
}
