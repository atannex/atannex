<?php

namespace Atannex\Traits;

use App\Contracts\Sluggable;

/**
 * Trait Bootable
 *
 * Provides dynamic slug path building for Sluggable models.
 *
 * @mixin Sluggable
 */
trait Bootable
{
    /**
     * Build a slug path dynamically, using hierarchy if available.
     */
    public function buildDynamicSlugPath(): ?string
    {
        return self::buildSlugPath($this->getSlugBase(), $this->getSlug());
    }

    /**
     * Build a full slug path from base and slug segment.
     *
     * Normalizes the slug: trims, lowercases, replaces spaces with dashes.
     */
    public static function buildSlugPath(?string $base, ?string $slug): ?string
    {
        if (!$slug) {
            return null;
        }

        $slug = strtolower(trim($slug));
        $slug = preg_replace('/\s+/', '-', $slug);

        if ($base) {
            $base = rtrim($base, '/');
            return sprintf('%s/%s', $base, $slug);
        }

        return $slug;
    }
}
