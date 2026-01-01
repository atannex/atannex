<?php

if (!function_exists('normalizeIds')) {
    /**
     * Normalize any scalar or iterable of IDs into a flat array.
     *
     * @param  int|string|iterable<int|string>  $ids
     * @return array<int|string>
     */
    function normalizeIds(int|string|iterable $ids): array
    {
        return is_iterable($ids)
            ? array_values(is_array($ids) ? $ids : iterator_to_array($ids, false))
            : [$ids];
    }
}
