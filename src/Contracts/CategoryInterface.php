<?php

namespace Atannex\Contracts;

use App\Models\Posts\Post;
use App\Models\Regions\Category;
use App\Models\Regions\Employee;
use App\Models\Regions\Region;
use App\Models\Tags\Tag;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CategoryInterface
{
    // Interface constants must be public
    public const PAGINATION_DEFAULT_LISTING = 15;  // category, region, date, author

    public const PAGINATION_RECENT_POSTS = 5;   // recent posts widget

    public const PAGINATION_POPULAR_TAGS = 12;  // popular tags

    public const PAGINATION_POSTS_BY_TAG = 20;  // posts by tag

    /**
     * Paginate posts within the category and its children.
     */
    public function postsByCategory(
        Category $category,
        int $limit = self::PAGINATION_DEFAULT_LISTING
    ): LengthAwarePaginator;

    /**
     * Paginate posts filtered by region.
     */
    public function postsByRegion(
        Region $region,
        int $limit = self::PAGINATION_DEFAULT_LISTING
    ): LengthAwarePaginator;

    /**
     * Paginate posts filtered by year/month.
     */
    public function postsByDate(
        string $yearMonth,
        int $limit = self::PAGINATION_DEFAULT_LISTING
    ): LengthAwarePaginator;

    /**
     * Get related categories in the same hierarchy.
     */
    public function relatedCategories(Category $category): Collection;

    /**
     * Get recent posts, optionally excluding the given post.
     */
    public function recentPosts(
        ?Post $post = null,
        int $limit = self::PAGINATION_RECENT_POSTS
    ): Collection;


    /**
     * Get popular tags used within the post’s category tree.
     */
    public function popularTags(
        Tag $tag,
        int $limit = self::PAGINATION_POPULAR_TAGS
    ): Collection;

    /**
     * Paginate posts filtered by tag.
     */
    public function postsByTag(
        Tag $tag,
        int $limit = self::PAGINATION_POSTS_BY_TAG
    ): LengthAwarePaginator;

    /**
     * Get category suggestions related to a tag context.
     */
    public function relatedCategoriesByTag(Tag $tag): Collection;

    /**
     * Get published social media profiles for the employee.
     */
    public function employeeSocial(Employee $employee): Collection;

    /**
     * Paginate posts filtered by author slug.
     */
    public function postsByAuthor(
        string $slug,
        int $limit = self::PAGINATION_DEFAULT_LISTING
    ): LengthAwarePaginator;
}
