<?php

namespace Atannex\Contracts;

use App\Models\Tags\Tag;
use App\Models\Posts\Post;
use App\Models\Regions\Region;
use App\Models\Regions\Category;
use App\Models\Regions\Employee;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryInterface
{
    /**
     * Paginate posts within the category and its children.
     */
    public function postsByCategory(Category $category, int $limit = 15): LengthAwarePaginator;

    /**
     * Paginate posts filtered by region.
     */
    public function postsByRegion(Region $region, int $limit = 15): LengthAwarePaginator;

    /**
     * Paginate posts filtered by year/month.
     */
    public function postsByDate(string $yearMonth, int $limit = 15): LengthAwarePaginator;

    /**
     * Get related categories in the same hierarchy.
     */
    public function relatedCategories(Category $category): Collection;

    /**
     * Get recent posts excluding the given post.
     */
    public function recentPosts(Post $post, int $limit = 5): Collection;

    /**
     * Get popular tags used within the post’s category tree.
     */
    public function popularTags(Tag $tag, int $limit = 12): Collection;

    /**
     * Paginate posts filtered by tag.
     */
    public function postsByTag(Tag $tag, int $limit = 20): LengthAwarePaginator;

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
    public function postsByAuthor(string $slug, int $limit = 15): LengthAwarePaginator;
}
