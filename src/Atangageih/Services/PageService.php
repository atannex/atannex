<?php

namespace Atangageih\Services;

use App\Models\Pages\Page;
use Illuminate\Support\Collection;
use Atangageih\Contracts\PageInterface;

/**
 * Service layer for handling page-related operations.
 */
final class PageService
{
    public function __construct(
        protected readonly PageInterface $interface
    ) {
        //
    }

    /**
     * Retrieve a specific active home page by slug.
     *
     * @param string $slug
     * @return Page|null
     */
    public function getHomePage(string $slug): ?Page
    {
        return $this->interface->getHomePage($slug);
    }

    /**
     * Retrieve all top-level published page categories.
     *
     * @return Collection<int, \App\Models\Pages\Category>
     */
    public function getAllCategoryPages(): Collection
    {
        return $this->interface->getAllCategoryPages();
    }

    /**
     * Retrieve all published and active home pages.
     *
     * @return Collection<int, Page>
     */
    public function getAllHomePages(): Collection
    {
        return $this->interface->getAllHomePages();
    }
}
