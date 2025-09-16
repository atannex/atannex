<?php

namespace Atannex\Contracts;

use App\Models\Pages\Page;
use Illuminate\Support\Collection;

/**
 * Interface PageInterface
 *
 * Defines the contract for page retrieval and categorization logic.
 * Assumes all inputs are valid and does not handle errors internally.
 */
interface PageInterface
{
    /**
     * Retrieve a homepage by its slug path.
     *
     * @param string $slug_path The unique slug identifier for the homepage.
     * @return Page The matching Page instance.
     */
    public function getHomePage(string $slug_path): ?Page;

    /**
     * Get a collection of all category-related pages.
     *
     * @return Collection<int, Page> Collection of category Page instances.
     */
    public function getAllCategoryPages(): Collection;

    /**
     * Get a collection of all home pages.
     *
     * @return Collection<int, Page> Collection of homepage Page instances.
     */
    public function getAllHomePages(): Collection;
}
