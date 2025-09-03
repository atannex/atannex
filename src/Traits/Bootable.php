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
     *
     * @return string|null
     */
    public function buildDynamicSlugPath(): ?string
    {
        return self::buildSlugPath($this->getSlugBase(), $this->getSlug());
    }

    /**
     * Build a full slug path from base and slug segment.
     *
     * @param string|null $base
     * @param string|null $slug
     * @return string|null
     */
    public static function buildSlugPath(?string $base, ?string $slug): ?string
    {
        if (!$slug) {
            return null;
        }

        return $base ? rtrim($base, '/') . '/' . ltrim($slug, '/') : $slug;
    }
}
