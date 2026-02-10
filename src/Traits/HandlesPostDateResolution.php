<?php

declare(strict_types=1);

namespace Atannex\Traits;

use App\Models\Posts\Post;

trait HandlesPostDateResolution
{
    /**
     * Resolve a post archive context from a date-based slug.
     *
     * Supported formats:
     *  - YYYY
     *  - YYYY/MM
     *
     * Returns resolution metadata only (no models).
     */
    protected function resolvePostArchiveBySlug(string $slug): ?array
    {
        [$year, $month] = $this->extractDateParts($slug);

        if (! $this->isValidYear($year) || ! $this->postsExist($year)) {
            return null;
        }

        if ($this->isValidMonth($month) && $this->postsExist($year, $month)) {
            return [
                'type'  => 'month',
                'year'  => (int) $year,
                'month' => (int) $month,
            ];
        }

        return [
            'type'  => 'year',
            'year'  => (int) $year,
            'month' => null,
        ];
    }

    /**
     * Extract year and optional month from a slug.
     */
    protected function extractDateParts(string $slug): array
    {
        return array_pad(
            explode('/', trim($slug, '/'), 2),
            2,
            null
        );
    }

    /**
     * Check if published posts exist for a given year (and optional month).
     */
    protected function postsExist(string $year, ?string $month = null): bool
    {
        $query = Post::published()
            ->whereYear('published_at', $year);

        if ($month !== null) {
            $query->whereMonth('published_at', $month);
        }

        return $query->exists();
    }

    /**
     * Validate a four-digit year within an acceptable range.
     */
    protected function isValidYear(?string $year): bool
    {
        if (! is_string($year) || ! preg_match('/^\d{4}$/', $year)) {
            return false;
        }

        $yearInt = (int) $year;
        $current = (int) date('Y');

        return $yearInt >= 1900 && $yearInt <= $current + 1;
    }

    /**
     * Validate a two-digit month (01–12).
     */
    protected function isValidMonth(?string $month): bool
    {
        return is_string($month)
            && preg_match('/^(0[1-9]|1[0-2])$/', $month) === 1;
    }
}
