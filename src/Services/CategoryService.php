<?php

declare(strict_types=1);

namespace Atannex\Services;

use App\Models\Tags\Tag;
use App\Models\Posts\Post;
use Atannex\Helpers\Media;
use App\Models\Pages\Category;
use App\Models\Regions\Region;
use App\Models\Regions\Employee;
use Illuminate\Support\Collection;
use Atannex\Contracts\CategoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Service class responsible for category-related operations.
 *
 * This class acts as a domain-level orchestrator between controllers and the
 * data access layer (repository/contract). It centralizes category-related
 * business logic and transforms data where necessary before presentation.
 */
final class CategoryService
{
    use Media;

    protected const DEFAULT_PAGINATION_LIMIT      = 50;

    protected const DEFAULT_RECENT_POSTS_LIMIT    = 5;

    protected const DEFAULT_POPULAR_TAGS_LIMIT    = 12;

    /**
     * Initialize the CategoryService with a category repository implementation.
     *
     * @param CategoryInterface $interface Concrete implementation of the category contract.
     */
    public function __construct(
        protected readonly CategoryInterface $interface
    ) {}

    /**
     * Fetch paginated published posts for the given category and its descendant categories.
     *
     * @param Category $category The target category node.
     * @param int $limit Number of posts per page (defaults to 50).
     * @return LengthAwarePaginator Paginated result set of published posts.
     */
    public function getPostsByCategory(Category $category, int $limit = self::DEFAULT_PAGINATION_LIMIT): LengthAwarePaginator
    {
        return $this->interface->getPostsByCategory($category, $limit);
    }

    /**
     * Retrieve a paginated list of posts filtered by a specific region.
     *
     * This method fetches posts associated with the given Region entity,
     * returning a paginated result to simplify frontend display and
     * limit memory usage. The number of posts per page can be customized.
     *
     * @param Region $region The region entity used to filter posts.
     * @param int $limit Optional. The number of posts per page. Default is 15.
     *
     * @return LengthAwarePaginator A paginator instance containing the posts.
     */
    public function getPostsByRegion(Region $region, int $limit = 15): LengthAwarePaginator
    {
        return $this->interface->getPostsByRegion($region, $limit);
    }

    /**
     * Retrieve and transform the published social media profiles of a given employee.
     *
     * Applies transformation logic via the `mapSocialMedia()` helper and filters
     * out any invalid or incomplete entries before returning the results.
     *
     * @param Employee $user The employee for whom to retrieve social media profiles.
     * @return Collection A collection of cleaned, mapped social media profiles.
     */
    public function getPublishedEmployeeSocialMedia(Employee $user): Collection
    {
        return $this->interface->getPublishedEmployeeSocialMedia($user)
            ->map(fn($media) => $this->mapSocialMedia($media))
            ->filter()
            ->values();
    }

    /**
     * Get related categories for a specific category, based on hierarchy logic.
     *
     * Can include siblings, children, or other contextually relevant categories.
     *
     * @param Category $category The category to base the relation on.
     * @return Collection Related categories.
     */
    public function getRelatedCategoriesForCategory(Category $category): Collection
    {
        return $this->interface->getRelatedCategoriesForCategory($category);
    }

    /**
     * Retrieve recent posts related to the category tree of the given post.
     *
     * Commonly used for "related articles" or "you might also like" sections.
     *
     * @param Post|null $post The reference post to extract category context from.
     * @param int $limit Max number of recent posts to return (defaults to 5).
     * @return Collection A collection of recent posts.
     */
    public function getRecentPosts(?Post $post, int $limit = self::DEFAULT_RECENT_POSTS_LIMIT): Collection
    {
        return $this->interface->getRecentPosts($post, $limit);
    }

    /**
     * Fetch the most popular tags related to the tag's category tree.
     *
     * Useful for building tag clouds or recommending trending tags.
     *
     * @param Tag|null $tag A tag whose associated post's category tree will be used.
     * @param int $limit Max number of tags to retrieve (default: 12).
     * @return Collection Most-used tags with associated post counts.
     */
    public function getPopularTagsByTagCategoryTree(?Tag $tag, int $limit = self::DEFAULT_POPULAR_TAGS_LIMIT): Collection
    {
        return $this->interface->getPopularTagsByTagCategoryTree($tag, $limit);
    }

    /**
     * Get paginated list of published posts filtered by the given tag.
     *
     * @param Tag $tag The tag to filter by.
     * @param int $limit Number of results per page (default: 50).
     * @return LengthAwarePaginator Paginated collection of posts tagged accordingly.
     */
    public function getPostsByTag(Tag $tag, int $limit = self::DEFAULT_PAGINATION_LIMIT): LengthAwarePaginator
    {
        return $this->interface->getPostsByTag($tag, $limit);
    }

    /**
     * Retrieve categories related to the tag's associated post's category tree.
     *
     * This helps in expanding the discovery of related categories based on content tags.
     *
     * @param Tag $tag The tag from which to infer category relationships.
     * @return Collection Related categories derived from the tag context.
     */
    public function getRelatedCategoriesForTag(Tag $tag): Collection
    {
        return $this->interface->getRelatedCategoriesForTag($tag);
    }

    /**
     * Retrieve paginated published posts written by an author via slug path.
     *
     * Designed to power author pages or public author feeds.
     *
     * @param string $slugPath The unique slug of the author.
     * @param int $limit Pagination limit (default: 15).
     * @return LengthAwarePaginator Paginated list of authored posts.
     */
    public function getPostsByAuthor(string $slugPath, int $limit = 15): LengthAwarePaginator
    {
        return $this->interface->getPostsByAuthor($slugPath, $limit);
    }
}
