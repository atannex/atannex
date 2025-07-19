<?php

namespace Atangageih\Services;

use App\Models\Tags\Tag;
use App\Models\Posts\Post;
use App\Models\Pages\Category;
use App\Models\Regions\Employee;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Atangageih\Services\Traits\Helper;
use Atangageih\Contracts\CategoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Service class for handling category-related business logic.
 *
 * Acts as a facade to the CategoryInterface, providing a clean and consistent API
 * for category operations while delegating data access to the underlying repository.
 */
final class CategoryService
{
    use Helper;

    /**
     * Create a new CategoryService instance.
     *
     * @param CategoryInterface $interface The category contract implementation.
     */
    public function __construct(
        protected readonly CategoryInterface $interface
    ) {}

    /**
     * Retrieve paginated posts for a category and its descendants.
     *
     * Fetches published posts associated with the specified category and its subcategories,
     * returned in a paginated format for efficient data handling.
     *
     * @param Category $category The category to retrieve posts from.
     * @param int $limit Number of posts per page (default: 50).
     * @return LengthAwarePaginator Paginated collection of posts.
     */
    public function getPostsByCategory(Category $category, int $limit = self::DEFAULT_PAGINATION_LIMIT): LengthAwarePaginator
    {
        return $this->interface->getPostsByCategory($category, $limit);
    }

    /**
     * Retrieve published social media profiles for a given user.
     *
     * Maps and filters the user's published social media profiles, ensuring valid data
     * and returning them in a clean collection.
     *
     * @param Employee $user The user to fetch social media profiles for.
     * @return Collection Collection of mapped and filtered social media profiles.
     */
    public function getPublishedEmployeeSocialMedia(Employee $user): Collection
    {
        $cacheKey = "published_user_social_media_transformed_{$user->id}";

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($user) {
            return $this->interface->getPublishedEmployeeSocialMedia($user)
                ->map(fn($media) => $this->mapSocialMedia($media))
                ->filter()
                ->values();
        });
    }

    /**
     * Retrieve categories related to the specified category.
     *
     * Returns a collection of related categories, such as siblings or subcategories,
     * based on the application's category hierarchy logic.
     *
     * @param Category $category The category to find related categories for.
     * @return Collection Collection of related Category instances.
     */
    public function getRelatedCategoriesForCategory(Category $category): Collection
    {
        return $this->interface->getRelatedCategoriesForCategory($category);
    }

    /**
     * Retrieve recent posts from categories related to the given post.
     *
     * Fetches a collection of recent posts for recommendation purposes, such as
     * "You may also like" sections, based on the post's category.
     *
     * @param Post $post The reference post to base the query on.
     * @param int $limit Maximum number of recent posts to retrieve (default: 5).
     * @return Collection Collection of recent Post instances.
     */
    public function getRecentPosts(?Post $post, int $limit = self::DEFAULT_RECENT_POSTS_LIMIT): Collection
    {
        return $this->interface->getRecentPosts($post, $limit);
    }

    /**
     * Retrieve popular tags within the category tree of a tag's associated post.
     *
     * Fetches tags with the highest count of published posts within the category tree
     * of the tag's associated post, useful for trending or relevant tag displays.
     *
     * @param Tag|null $tag The tag to base the category tree on, or null for no filtering.
     * @param int $limit Maximum number of popular tags to retrieve (default: 12).
     * @return Collection Collection of popular Tag instances with post counts.
     */
    public function getPopularTagsByTagCategoryTree(?Tag $tag, int $limit = self::DEFAULT_POPULAR_TAGS_LIMIT): Collection
    {
        return $this->interface->getPopularTagsByTagCategoryTree($tag, $limit);
    }

    /**
     * Retrieve paginated posts associated with a specific tag.
     *
     * Fetches published posts that have the specified tag, returned in a paginated format.
     *
     * @param Tag $tag The tag to filter posts by.
     * @param int $limit Number of posts per page (default: 50).
     * @return LengthAwarePaginator Paginated collection of posts.
     */
    public function getPostsByTag(Tag $tag, int $limit = self::DEFAULT_PAGINATION_LIMIT): LengthAwarePaginator
    {
        return $this->interface->getPostsByTag($tag, $limit);
    }

    /**
     * Retrieve categories related to the category tree of a given tag.
     *
     * Fetches related categories, such as siblings or leaf categories, within the
     * category tree of the first post associated with the tag.
     *
     * @param Tag $tag The tag to base the category query on.
     * @return Collection Collection of related Category instances.
     */
    public function getRelatedCategoriesForTag(Tag $tag): Collection
    {
        return $this->interface->getRelatedCategoriesForTag($tag);
    }

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
    public function getPostsByAuthor(string $slugPath, int $limit = 15): LengthAwarePaginator
    {
        return $this->interface->getPostsByAuthor($slugPath, $limit);
    }
}
