<?php

declare(strict_types=1);

namespace Atannex\Traits;

use App\Models\Posts\Post;

trait HandlesPostDateResolution
{
    /**
     * Resolve a post archive context for a given year.
     *
     * Returns resolution metadata only (no models).
     */
    protected function resolvePostArchiveYear(string $year): ?array
    {
        if (! $this->isValidYear($year) || ! $this->postsExistForYear($year)) {
            return null;
        }

        return [
            'type' => 'year',
            'year' => (int) $year,
            'month' => null,
        ];
    }

    /**
     * Resolve a post archive context for a given year and month.
     *
     * Returns resolution metadata only (no models).
     */
    protected function resolvePostArchiveMonth(string $year, string $month): ?array
    {
        if (! $this->isValidYear($year) || ! $this->isValidMonth($month)) {
            return null;
        }

        if (! $this->postsExistForMonth($year, $month)) {
            return null;
        }

        return [
            'type' => 'month',
            'year' => (int) $year,
            'month' => (int) $month,
        ];
    }

    /**
     * Check if published posts exist for a given year.
     */
    protected function postsExistForYear(string $year): bool
    {
        return Post::published()
            ->whereYear('published_at', $year)
            ->exists();
    }

    /**
     * Check if published posts exist for a given year and month.
     */
    protected function postsExistForMonth(string $year, string $month): bool
    {
        return Post::published()
            ->whereYear('published_at', $year)
            ->whereMonth('published_at', $month)
            ->exists();
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
