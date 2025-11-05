<?php

declare(strict_types=1);

namespace Atannex\Services;

use App\Models\Tags\Tag;
use App\Models\Posts\Post;
use App\Models\Regions\Category;
use App\Models\Regions\Region;
use App\Models\Regions\Employee;
use Illuminate\Support\Collection;
use Atannex\Contracts\CategoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Domain layer for category-related operations.
 */
final class CategoryService
{
    protected const PAGINATION_LIMIT = 15;

    protected const RECENT_LIMIT = 5;

    protected const POPULAR_LIMIT = 12;

    public function __construct(
        protected readonly CategoryInterface $interface
    ) {}

    /**
     * Paginate posts in category tree.
     */
    public function postsByCategory(Category $category, int $limit = self::PAGINATION_LIMIT): LengthAwarePaginator
    {
        return $this->interface->postsByCategory($category, $limit);
    }

    /**
     * Paginate posts filtered by region.
     */
    public function postsByRegion(Region $region, int $limit = self::PAGINATION_LIMIT): LengthAwarePaginator
    {
        return $this->interface->postsByRegion($region, $limit);
    }

    /**
     * Paginate posts filtered by year and month.
     */
    public function postsByDate(string $yearMonth, int $limit = self::PAGINATION_LIMIT): LengthAwarePaginator
    {
        return $this->interface->postsByDate($yearMonth, $limit);
    }

    /**
     * Published employee social profiles.
     */
    public function employeeSocial(Employee $employee): Collection
    {
        return $this->interface->employeeSocial($employee);
    }

    /**
     * Related categories in context.
     */
    public function relatedCategories(Category $category): Collection
    {
        return $this->interface->relatedCategories($category);
    }

    /**
     * Recent posts related to the same category tree.
     */
    public function recentPosts(Post $post, int $limit = self::RECENT_LIMIT): Collection
    {
        return $this->interface->recentPosts($post, $limit);
    }

    /**
     * Popular tags in associated category context.
     */
    public function popularTags(Tag $tag, int $limit = self::POPULAR_LIMIT): Collection
    {
        return $this->interface->popularTags($tag, $limit);
    }

    /**
     * Paginate posts filtered by tag.
     */
    public function postsByTag(Tag $tag, int $limit = self::PAGINATION_LIMIT): LengthAwarePaginator
    {
        return $this->interface->postsByTag($tag, $limit);
    }

    /**
     * Category suggestions based on tag.
     */
    public function relatedCategoriesByTag(Tag $tag): Collection
    {
        return $this->interface->relatedCategoriesByTag($tag);
    }

    /**
     * Paginate posts from an author.
     */
    public function postsByAuthor(string $slug, int $limit = self::PAGINATION_LIMIT): LengthAwarePaginator
    {
        return $this->interface->postsByAuthor($slug, $limit);
    }
}
