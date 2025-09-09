<?php

declare(strict_types=1);

namespace Atannex\Views\Traits;

trait CanNormalize
{
    /**
     * Normalizes input IDs into an array of integers or strings.
     *
     * @param int|string|iterable<int|string>|null $ids
     * @return array<int|string>
     */
    private function normalizeIds(int|string|iterable|null $ids): array
    {
        if ($ids === null) {
            return [];
        }

        if (is_iterable($ids)) {
            $result = [];
            foreach ($ids as $id) {
                if (is_int($id) || is_string($id)) {
                    $result[] = $id;
                }
            }

            return $result;
        }

        return [$ids];
    }
}
