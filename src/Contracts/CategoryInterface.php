<?php

namespace Atannex\Contracts;

use App\Models\Tags\Tag;
use App\Models\Posts\Post;
use App\Models\Pages\Category;
use App\Models\Regions\Employee;
use App\Models\Regions\Region;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface defining the contract for category-related operations.
 *
 * This interface provides a consistent API for managing categories, posts, tags,
 * and related user social media within the application. Implementing classes
 * must handle data retrieval and query logic according to the defined method signatures.
 */
interface CategoryInterface
{
    /**
     * Retrieve paginated posts for a category and all its descendant categories.
     *
     * @param Category $category The category to fetch posts from.
     * @param int $limit Number of posts per page (default: 15).
     * @return LengthAwarePaginator Paginated collection of posts.
     */
    public function getPostsByCategory(Category $category, int $limit = 15): LengthAwarePaginator;

    /**
     * Retrieve paginated posts filtered by a specific region.
     *
     * @param Region $region The region entity used to filter posts.
     * @param int $limit Number of posts per page (default: 15).
     * @return LengthAwarePaginator Paginated collection of posts for the region.
     */
    public function getPostsByRegion(Region $region, int $limit = 15): LengthAwarePaginator;

    /**
     * Retrieve paginated posts filtered by a specific year and month.
     *
     * @param string $yearMonth The year/month string in "YYYY/MM" format.
     * @param int $perPage Number of posts per page (default: 15).
     * @return LengthAwarePaginator Paginated collection of posts for the date range.
     */
    public function getPostsByDate(string $yearMonth, int $perPage = 15): LengthAwarePaginator;

    /**
     * Retrieve categories related to the specified category.
     *
     * Typically includes sibling or child categories within the category hierarchy.
     *
     * @param Category $category The reference category.
     * @return Collection Collection of related Category instances.
     */
    public function getRelatedCategoriesForCategory(Category $category): Collection;

    /**
     * Retrieve recent posts relative to a given post.
     *
     * Useful for displaying recommended or contextually relevant posts.
     *
     * @param Post $post The reference post.
     * @param int $limit Maximum number of recent posts to retrieve (default: 5).
     * @return Collection Collection of recent Post instances.
     */
    public function getRecentPosts(Post $post, int $limit = 5): Collection;

    /**
     * Retrieve popular tags within the category tree of a tag's associated post.
     *
     * Useful for showing trending or relevant tags within a specific category context.
     *
     * @param Tag $tag The tag to base the category tree on.
     * @param int $limit Maximum number of tags to retrieve (default: 12).
     * @return Collection Collection of popular Tag instances with post counts.
     */
    public function getPopularTagsByTagCategoryTree(Tag $tag, int $limit = 12): Collection;

    /**
     * Retrieve paginated posts associated with a specific tag.
     *
     * @param Tag $tag The tag to filter posts by.
     * @param int $limit Number of posts per page (default: 20).
     * @return LengthAwarePaginator Paginated collection of posts with the tag.
     */
    public function getPostsByTag(Tag $tag, int $limit = 20): LengthAwarePaginator;

    /**
     * Retrieve categories related to a tag's associated post category tree.
     *
     * Useful for navigating or recommending related categories in context.
     *
     * @param Tag $tag The tag to base the category query on.
     * @return Collection Collection of related Category instances.
     */
    public function getRelatedCategoriesForTag(Tag $tag): Collection;

    /**
     * Retrieve published social media profiles for a given employee/user.
     *
     * @param Employee $employee The user whose social media profiles are requested.
     * @return Collection Collection of published social media profiles.
     */
    public function getPublishedEmployeeSocialMedia(Employee $employee): Collection;

    /**
     * Retrieve paginated posts by an author identified by their slug.
     *
     * @param string $slug_path The author's unique slug identifier.
     * @param int $limit Number of posts per page (default: 15).
     * @return LengthAwarePaginator Paginated collection of posts authored by the user.
     */
    public function getPostsByAuthor(string $slug_path, int $limit = 15): LengthAwarePaginator;
}
