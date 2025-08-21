<?php

namespace Atannex\Views\Traits;


trait Normalize
{
    /**
     * Normalize IDs input to always be an array
     *
     * @param mixed $ids
     * @return array
     */
    private function normalizeIds($ids): array
    {
        if (is_array($ids)) {
            return $ids;
        }

        if (is_string($ids) || is_numeric($ids)) {
            return [$ids];
        }

        return [];
    }
}
