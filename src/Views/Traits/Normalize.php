<?php

declare(strict_types=1);

namespace Atannex\Views\Traits;

trait Normalize
{
    /**
     * Normalizes input IDs into an array.
     *
     * Accepts a single ID (int|string), an iterable of IDs, or null.
     * Returns an empty array for null or invalid inputs.
     *
     * @param int|string|iterable|null $ids
     * @return array<int|string>
     */
    private function normalizeIds($ids): array
    {
        if ($ids === null) {
            return [];
        }

        if (is_array($ids)) {
            return $ids;
        }

        if (is_iterable($ids)) {
            return iterator_to_array($ids);
        }

        return is_int($ids) || is_string($ids) ? [$ids] : [];
    }
}
