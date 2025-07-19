<?php

namespace Morfaw\Supports;

use Illuminate\Support\Collection;

/**
 * Trait SlugPath
 *
 * Provides hierarchical slug path generation for models with parent-child relationships.
 */
trait EnablePath
{
    /**
     * Build a slug path string by joining an array/collection of slugs.
     *
     * @param Collection<string> $slugs
     * @param string $separator
     * @return string
     */
    protected function buildSlugPath(Collection $slugs, string $separator = '/'): string
    {
        $filteredSlugs = $slugs->filter(fn($slug) => is_string($slug) && trim($slug) !== '');

        return $filteredSlugs->implode($separator);
    }

    /**
     * Get the full slug path attribute by traversing up the parent chain.
     *
     * @return string
     */
    public function getSlugPathAttribute(): string
    {
        $slugs = collect();
        $current = $this;

        while ($current !== null) {
            if (!empty($current->slug)) {
                $slugs->push($current->slug);
            }
            $current = $current->parent ?? null;
        }

        return $this->buildSlugPath($slugs->reverse()->values());
    }
}
