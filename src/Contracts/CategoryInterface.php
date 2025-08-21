<?php

namespace Atannex\Contracts;

use App\Models\Tags\Tag;
use App\Models\Posts\Post;
use App\Models\Pages\Category;
use App\Models\Regions\Employee;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface defining the contract for category-related operations.
 *
 * Provides methods for retrieving posts, related categories, recent posts, popular tags,
 * and user social media within a category structure.
 */
interface CategoryInterface
{
    /**
     * Retrieve paginated posts for a category and its descendants.
     *
     * Fetches published posts associated with the specified category and its subcategories,
     * returned in a paginated format for efficient data handling.
     *
     * @param Category $category The category to fetch posts from.
     * @param int $limit Number of posts per page (default: 15).
     * @return LengthAwarePaginator Paginated collection of posts.
     */
    public function getPostsByCategory(Category $category, int $limit = 15): LengthAwarePaginator;

    /**
     * Retrieve categories related to the specified category.
     *
     * Returns a collection of categories that are contextually related, such as siblings or
     * parent categories, based on the application's category hierarchy logic.
     *
     * @param Category $category The category to find related categories for.
     * @return Collection Collection of related Category instances.
     */
    public function getRelatedCategoriesForCategory(Category $category): Collection;

    /**
     * Retrieve recent posts relative to a given post.
     *
     * Fetches a collection of recent posts, typically excluding the reference post,
     * based on the category of the provided post, suitable for recommendations or listings.
     *
     * @param Post $post The reference post to base the query on.
     * @param int $limit Maximum number of recent posts to retrieve (default: 5).
     * @return Collection Collection of recent Post instances.
     */
    public function getRecentPosts(Post $post, int $limit = 5): Collection;

    /**
     * Retrieve popular tags within the category tree of a tag's associated post.
     *
     * Fetches tags with the highest count of published posts within the category tree
     * of the tag's associated post, useful for displaying trending or relevant tags.
     *
     * @param Tag|null $tag The tag to base the category tree on, or null for no filtering.
     * @param int $limit Maximum number of popular tags to retrieve (default: 12).
     * @return Collection Collection of popular Tag instances with post counts.
     */
    public function getPopularTagsByTagCategoryTree(?Tag $tag, int $limit = 12): Collection;

    /**
     * Retrieve paginated posts associated with a specific tag.
     *
     * Fetches published posts that have the specified tag, returned in a paginated format.
     *
     * @param Tag $tag The tag to filter posts by.
     * @param int $limit Number of posts per page (default: 20).
     * @return LengthAwarePaginator Paginated collection of posts.
     */
    public function getPostsByTag(Tag $tag, int $limit = 20): LengthAwarePaginator;

    /**
     * Retrieve related categories within the category tree of a given tag.
     *
     * Fetches categories related to the tag's associated post's category tree,
     * useful for contextual navigation or recommendations.
     *
     * @param Tag $tag The tag to base the category query on.
     * @return Collection Collection of related Category instances.
     */
    public function getRelatedCategoriesForTag(Tag $tag): Collection;

    /**
     * Retrieve published social media profiles for a given user.
     *
     * Fetches a collection of the user's social media profiles that are marked as published,
     * ordered by display order.
     *
     * @param Employee $user The user to fetch social media profiles for.
     * @return Collection Collection of published social media profiles.
     */
    public function getPublishedEmployeeSocialMedia(Employee $employee): Collection;

    /**
     * Retrieve paginated posts by an author identified by their slug.
     *
     * Fetches published posts authored by the user with the specified slug,
     * returned in a paginated format.
     *
     * @param string $slugPath The author's unique slug.
     * @param int $limit Number of posts per page (default: 15).
     * @return LengthAwarePaginator Paginated collection of posts.
     */
    public function getPostsByAuthor(string $slugPath, int $limit = 15): LengthAwarePaginator;
}
