<?php

namespace Atannex\Repositories;

use App\Models\Pages\Page;
use App\Models\Pages\Category;
use Illuminate\Support\Collection;
use Atannex\Contracts\PageInterface;
use Atannex\Helpers\Query;

class PageRepository implements PageInterface
{
    use Query;

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
            ->with(['children' => $this->publishedChildren()])
            ->get();
    }

    /**
     * Retrieve all published and active home pages.
     *
     * @return Collection<int, Page>
     */
    public function getAllHomePages(): Collection
    {
        return $this->activePageQuery()->get();
    }

    /**
     * Retrieve a single active home page by slug, including only active sections and widgets.
     */
    public function getHomePage(string $slug): ?Page
    {
        return $this->activePageQuery()
            ->where('slug', $slug)
            ->with(['sections' => $this->activeSections()])
            ->first();
    }
}
