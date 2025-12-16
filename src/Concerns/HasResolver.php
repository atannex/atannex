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
    protected function resolveAuthor(string $slug): ?Employee
    {
        return Employee::with('user')
            ->where('status', Status::ACTIVE)
            ->whereHas('user', fn($q) => $q->where('slug', $slug))
            ->first();
    }

    protected function resolveCategory(string $slug): ?Category
    {
        return Category::where([
            ['flag', Flag::PUBLISHED],
            ['slug_path', $slug],
        ])->first();
    }

    protected function resolveTag(string $slug): ?Tag
    {
        return Tag::where('slug', $slug)->first();
    }

    protected function resolvePost(string $slug): ?Post
    {
        return Post::published()
            ->where('slug_path', $slug)
            ->first();
    }

    /**
     * Resolve posts by year or year/month slug.
     * Examples:
     * - 2024          → year archive (if posts exist)
     * - 2024/03       → month archive if posts exist in that month
     *                   → otherwise fall back to year archive if year has posts
     */
    protected function resolvePostByDate(string $slug): ?array
    {
        [$year, $month] = $this->extractDateParts($slug);

        if (! $this->isValidYear($year)) {
            return null;
        }

        $yearQuery = $this->queryByYear($year);
        if (! $yearQuery->exists()) {
            return null;
        }

        if ($month && $this->isValidMonth($month)) {
            $monthQuery = $this->queryByMonth($year, $month);

            if ($monthQuery->exists()) {
                return [
                    'type'  => 'month',
                    'year'  => $year,
                    'month' => $month,
                ];
            }
        }

        return [
            'type' => 'year',
            'year' => $year,
        ];
    }

    protected function extractDateParts(string $slug): array
    {
        return array_pad(explode('/', trim($slug, '/'), 2), 2, null);
    }

    protected function isValidYear(?string $year): bool
    {
        return $year && preg_match('/^\d{4}$/', $year);
    }

    protected function isValidMonth(?string $month): bool
    {
        return $month && preg_match('/^(0[1-9]|1[0-2])$/', $month);
    }

    protected function queryByYear(string $year)
    {
        return Post::published()
            ->whereYear('published_at', $year);
    }

    protected function queryByMonth(string $year, string $month)
    {
        return Post::published()
            ->whereYear('published_at', $year)
            ->whereMonth('published_at', $month);
    }
}
