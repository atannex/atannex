<?php

namespace Atannex\Concerns;

use App\Enums\Flag;
use App\Enums\Status;
use App\Models\Posts\Post;
use App\Models\Regions\Category;
use App\Models\Regions\Employee;
use App\Models\Tags\Tag;

trait HasResolver
{
    /**
     * Resolve an Employee (author) by user slug.
     * Consider caching this if frequently accessed.
     */
    protected function resolveAuthor(string $slug): ?Employee
    {
        // Good: eager loads user, filters active employees
        // Suggestion: add index on users.slug + employees.status if not already
        return Employee::with('user')
            ->where('status', Status::ACTIVE)
            ->whereHas('user', fn($q) => $q->where('slug', $slug))
            ->first();
    }

    /**
     * Resolve a Category by full slug path.
     * Uses firstOrFail() — this will throw 404 if not found (good for routes).
     */
    protected function resolveCategory(string $slug): Category // Remove ? since firstOrFail() never returns null
    {
        return Category::where([
            ['flag', Flag::PUBLISHED],
            ['slug_path', $slug],
        ])->firstOrFail();
    }

    /**
     * Resolve a Tag by slug.
     * Consider adding ->where('status', 'published') or similar if tags can be unpublished.
     */
    protected function resolveTag(string $slug): ?Tag
    {
        return Tag::where('slug', $slug)->first();
    }

    /**
     * Resolve a Post by slug_path.
     * Consider scoping to published posts only unless intentionally allowing drafts/previews.
     */
    protected function resolvePost(string $slug): ?Post
    {
        // Suggestion: if this is for public routes, filter by published status/flag
        // return Post::where('slug_path', $slug)->first();
        // Or better:
        return Post::published()
            ->where('slug_path', $slug)
            ->first();
    }

    /**
     * Resolve archive context (year/month) from a slug like "2024" or "2024/05"
     * Returns structured data or null if invalid.
     */
    protected function resolvePostByDatePart(string $slug, string $part): ?array
    {
        [$year, $month] = $this->extractDateParts($slug);

        if (! $this->isValidDatePart($year, $month, $part)) {
            return null;
        }

        $post = $this->queryPostByDatePart($year, $month, $part)->first();

        // This checks existence of *any* post in that period — correct for validation
        if (! $post) {
            return null;
        }

        // Good formatting logic
        return [
            'type'  => $part,
            'value' => $post->published_at->format($part === 'month' ? 'm' : 'Y'),
            // Suggestion: also return formatted label?
            // 'label' => $post->published_at->translatedFormat($part === 'month' ? 'F Y' : 'Y'),
        ];
    }

    /**
     * Extract year and month from slug path like "2025/03" → ['2025', '03']
     * Fallback to null if not present.
     */
    protected function extractDateParts(string $slug): array
    {
        // array_pad is clever — ensures exactly 2 elements
        return array_pad(explode('/', $slug, 2), 2, null);
    }

    /**
     * Validate that the requested part (year/month) actually exists in the slug
     */
    protected function isValidDatePart(?string $year, ?string $month, string $part): bool
    {
        // Solid logic
        return ($part === 'year' && $year !== null) || ($part === 'month' && $month !== null);
    }

    /**
     * Build query for posts in a specific year or month
     */
    protected function queryPostByDatePart(?string $year, ?string $month, string $part)
    {
        $query = Post::whereNotNull('published_at')
            ->published(); // ← Strongly recommend adding a scope if not exists

        if ($part === 'year') {
            $query->whereYear('published_at', $year);
        } else { // month
            $query->whereMonth('published_at', $month);
            if ($year) {
                $query->whereYear('published_at', $year);
            }
        }

        return $query;
    }
}
