<?php

namespace Atangageih\Contracts;

use App\Models\Pages\Page;
use Illuminate\Support\Collection;

/**
 * Interface PageInterface
 *
 * Defines the contract for page retrieval and categorization logic.
 */
interface PageInterface
{
    /**
     * Retrieve a homepage by its slug path.
     *
     * @param string $slugPath
     * @return Page|null
     */
    public function getHomePage(string $slugPath): ?Page;

    /**
     * Get a collection of all category-related pages.
     *
     * @return Collection<int, Page>
     */
    public function getAllCategoryPages(): Collection;

    /**
     * Get a collection of all home pages.
     *
     * @return Collection<int, Page>
     */
    public function getAllHomePages(): Collection;
}
