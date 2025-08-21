<?php

namespace Atannex\Traits;

use App\Models\Pages\Category;
use App\Models\Pivots\PostTag;
use App\Models\Posts\Post;
use App\Models\Regions\Employee;

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
}
