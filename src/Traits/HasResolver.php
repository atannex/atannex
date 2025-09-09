<?php

namespace Atannex\Traits;

use App\Models\Posts\Post;
use App\Models\Pages\Category;
use App\Models\Pivots\PostTag;
use App\Models\Regions\Region;
use App\Models\Regions\Employee;
use Atannex\Helpers\Query;

/**
 * Trait Resolver
 *
 * Provides methods to resolve various model instances by their slug.
 */
trait HasResolver
{
    use Query;

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
    protected function resolveRegion(string $slug): ?Region
    {
        return Region::where('slug_path', $slug)->first();
    }

    /**
     * Resolves a post based on a date slug and a specified date part (year or month).
     *
     * This method parses a date slug to extract year and/or month components and queries
     * the database for the first published post matching the specified date part. If a
     * matching post is found, it returns an array with the date part type and its formatted
     * value. If no post is found or the required date component is missing, it returns null.
     *
     * @param string $slug The date slug (e.g., '2023' or '2023-10') to parse for year and/or month.
     * @param string $part The date part to resolve, either 'year' or 'month'.
     * @return array|null An array containing the date part type and its formatted value (e.g., ['type' => 'year', 'value' => '2023']),
     *                    or null if no post is found or the slug is invalid for the specified part.
     */
    protected function resolvePostByDatePart(string $slug, string $part): ?array
    {
        ['year' => $year, 'month' => $month] = $this->parseDateSlug($slug);

        if (!$year && $part === 'year') {
            return null;
        }

        if (!$month && $part === 'month') {
            return null;
        }

        $query = Post::query()->whereNotNull('published_at');

        if ($part === 'year') {
            $query->whereYear('published_at', $year);
        }

        if ($part === 'month') {
            $query->whereMonth('published_at', $month);
            if ($year) {
                $query->whereYear('published_at', $year);
            }
        }

        $post = $query->first();

        return $post ? [
            'type'  => $part,
            'value' => $post->published_at->format($part === 'month' ? 'm' : 'Y')
        ] : null;
    }
}
