<?php

namespace Atannex\Traits;

use App\Models\Pages\Category;
use App\Models\Pivots\PostTag;
use App\Models\Posts\Post;
use App\Models\Regions\Employee;
use App\Models\Regions\Region;

/**
 * Trait Resolver
 *
 * Provides methods to resolve various model instances by their slug.
 */
trait Resolver
{
    /**
     * Resolve an employee author by their user slug.
     *
     * @param string $slug The unique slug of the user
     * @return Employee|null The matching Employee instance or null if not found
     */
    protected function resolveAuthor(string $slug): ?Employee
    {
        return Employee::whereHas('user', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })->with('user')->first();
    }

    /**
     * Resolve a category by its slug path.
     *
     * @param string $slug The unique slug path of the category
     * @return Category|null The matching Category instance or null if not found
     */
    protected function resolveCategory(string $slug): ?Category
    {
        return Category::where('slug_path', $slug)->first();
    }

    /**
     * Resolve a post tag by its slug path.
     *
     * @param string $slug The unique slug path of the post tag
     * @return PostTag|null The matching PostTag instance or null if not found
     */
    protected function resolveTag(string $slug): ?PostTag
    {
        return PostTag::with(['tag', 'post.category'])
            ->where('slug_path', $slug)
            ->first();
    }

    /**
     * Resolve a post by its slug path.
     *
     * @param string $slug The unique slug path of the post
     * @return Post|null The matching Post instance or null if not found
     */
    protected function resolvePost(string $slug): ?Post
    {
        return Post::where('slug_path', $slug)->first();
    }

    /**
     * Resolve a region by its slug.
     *
     * This method queries the database for a Region record matching the provided slug.
     * It returns the first matching record or null if no match is found.
     *
     * @param string $slug The slug identifier of the region.
     * @return Region|null The matching Region instance, or null if not found.
     */
    protected function resolveRegion(string $slug):?Region
    {
        return Region::where('slug_path', $slug)->first();
    }

    protected function resolveDate(string $slug):?Post
    {
        return Post::where('date_path', $slug)->first();
    }
}
