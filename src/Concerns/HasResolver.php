<?php

namespace Atannex\Concerns;

use App\Enums\Flag;
use App\Enums\Status;
use App\Models\Posts\Post;
use App\Models\Regions\Category;
use App\Models\Regions\Employee;
use App\Models\Tags\Tag;
use Atannex\Traits\HasGlobal;

trait HasResolver
{
    use HasGlobal;

    /**
     * Check if an author exists by slug.
     */
    protected function authorExists(string $slug): bool
    {
        return Employee::where('status', Status::ACTIVE)
            ->whereHas('user', fn($q) => $q->where('slug', $slug))
            ->exists();
    }

    /**
     * Check if a category exists by slug.
     */
    protected function categoryExists(string $slug): bool
    {
        return Category::where([
            ['flag', Flag::PUBLISHED],
            ['slug_path', $slug],
        ])->exists();
    }

    /**
     * Check if a tag exists by slug.
     */
    protected function tagExists(string $slug): bool
    {
        return Tag::where('slug', $slug)->exists();
    }

    /**
     * Check if a post exists by slug.
     */
    protected function postExists(string $slug): bool
    {
        return Post::published()
            ->where('slug_path', $slug)
            ->exists();
    }

    /**
     * Resolve posts by year or year/month slug.
     *
     * Returns resolution metadata only — no models.
     */
    protected function resolvePostByDate(string $slug): ?array
    {
        [$year, $month] = $this->extractDateParts($slug);

        if (! $this->isValidYear($year)) {
            return null;
        }

        if (! $this->postsExistForYear($year)) {
            return null;
        }

        if ($month && $this->isValidMonth($month)) {
            if ($this->postsExistForMonth($year, $month)) {
                return [
                    'type'  => 'month',
                    'year'  => $year,
                    'month' => $month,
                ];
            }
        }

        return [
            'type'  => 'year',
            'year'  => $year,
            'month' => null,
        ];
    }

    /**
     * Helpers
     */
    protected function postsExistForYear(string $year): bool
    {
        return Post::published()
            ->whereYear('published_at', $year)
            ->exists();
    }

    protected function postsExistForMonth(string $year, string $month): bool
    {
        return Post::published()
            ->whereYear('published_at', $year)
            ->whereMonth('published_at', $month)
            ->exists();
    }
}
