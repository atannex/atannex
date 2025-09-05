<?php

namespace Atannex\Helpers;

trait Cache
{
    /**
     * Retrieve cached entities (per-request).
     */
    private function getCachedEntities(string $type, array $ids, callable $resolver)
    {
        static $entityCache = [];

        $cacheKey = $this->makeEntityCacheKey($type, $ids);

        if (!isset($entityCache[$cacheKey])) {
            $entityCache[$cacheKey] = $resolver($type, $ids);
        }

        return $entityCache[$cacheKey];
    }

    /**
     * Retrieve cached posts by tab config (per-request).
     */
    private function getCachedPosts(array $tab, callable $resolver)
    {
        static $postsCache = [];

        $postKey = $this->makeTabCacheKey($tab);

        if (!isset($postsCache[$postKey])) {
            $postsCache[$postKey] = $resolver($tab);
        }

        return $postsCache[$postKey];
    }

    /**
     * Generate a stable cache key for resolved entities.
     */
    private function makeEntityCacheKey(string $type, array $ids): string
    {
        sort($ids);
        return $type . ':' . implode(',', $ids);
    }

    /**
     * Generate a stable cache key for posts by tab config.
     */
    private function makeTabCacheKey(array $tab): string
    {
        $normalized = $this->recursiveKeySort($tab);
        return md5(json_encode($normalized));
    }

    private function recursiveKeySort(array $array): array
    {
        ksort($array);
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = $this->recursiveKeySort($value);
            }
        }
        return $array;
    }
}
