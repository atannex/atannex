<?php

if (!function_exists('normalizeIds')) {
    /**
     * Normalize a scalar ID or an iterable of IDs into a flat, zero-based indexed array.
     *
     * Iterable inputs are converted to a reindexed array of their values (iterable keys are discarded).
     * Non-iterable inputs are wrapped into a single-element array.
     *
     * @param int|string|iterable<int|string> $ids The ID or iterable of IDs to normalize; if iterable, only its values are preserved.
     * @return array<int|string> A zero-based indexed array containing the ID values.
     */
    function normalizeIds(int|string|iterable $ids): array
    {
        return is_iterable($ids)
            ? array_values(is_array($ids) ? $ids : iterator_to_array($ids, false))
            : [$ids];
    }
}