<?php

if (! function_exists('normalizeIds')) {
    /**
     * Normalize a scalar ID or iterable of IDs into a flat, zero-based array.
     *
     * Iterable inputs are converted to a reindexed array of their values.
     * Scalar inputs are wrapped in a single-element array.
     * Empty or null inputs result in an empty array.
     *
     * @param int|string|iterable<int|string>|null $ids
     * @return array<int, int|string>
     */
    function normalizeIds(int|string|iterable|null $ids): array
    {
        if ($ids === null || $ids === []) {
            return [];
        }

        if (is_iterable($ids)) {
            return array_values(
                is_array($ids)
                    ? $ids
                    : iterator_to_array($ids, false)
            );
        }

        return [$ids];
    }
}
