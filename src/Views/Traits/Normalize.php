<?php

namespace Atannex\Views\Traits;

trait Normalize
{
    /**
     * Normalize IDs input to always be an array.
     * Accepts scalar, iterable, or null values.
     *
     * @return array<int|string>
     */
    private function normalizeIds(int|string|iterable|null $ids): array
    {
        if ($ids === null) {
            return [];
        }

        if (is_iterable($ids)) {
            return is_array($ids) ? $ids : iterator_to_array($ids);
        }

        if (is_scalar($ids)) {
            return [$ids];
        }

        return [];
    }
}
