<?php

namespace Atannex\Repositories;

use App\Models\Pages\Page;
use App\Models\Pages\Category;
use Illuminate\Support\Collection;
use Atannex\Contracts\PageInterface;
use Atannex\Helpers\HasQuery;

/**
 * Repository handling page and category retrieval.
 *
 * Implements the PageInterface without internal error handling.
 * Assumes all data exists and queries always return valid results.
 */
class PageRepository implements PageInterface
{
    use HasQuery;

    /**
     * Retrieve all top-level published page categories with their children recursively loaded.
     *
     * @return Collection<int, Category> Collection of Category instances.
     */
    public function getAllCategoryPages(): Collection
    {
        return Category::query()
            ->published()
            ->whereNull('parent_id')
            ->with(['children' => $this->publishedChildren()])
            ->get();
    }

    /**
     * Retrieve all published and active home pages.
     *
     * @return Collection<int, Page> Collection of Page instances.
     */
    public function getAllHomePages(): Collection
    {
        return $this->activePageQuery()->get();
    }

    /**
     * Retrieve a single active home page by slug, including only active sections and widgets.
     *
     * @param string $slug The slug of the homepage.
     * @return Page The matching Page instance.
     */
    public function getHomePage(string $slug): ?Page
    {
        return $this->activePageQuery()
            ->where('slug', $slug)
            ->with(['sections' => $this->activeSections()])
            ->first();
    }
}
