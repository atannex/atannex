<?php

namespace Atannex\Builders;

use Atannex\Traits\Bootable;

trait RegionBuilder
{
    use Bootable;

    /**
     * ---------------------------
     * Sluggable Implementation
     * ---------------------------
     */

    /**
     * Get the base string for slug generation.
     *
     * Uses the parent region's slug_path if available.
     *
     * @return string|null
     */
    public function getSlugBase(): ?string
    {
        return $this->parent?->slug_path;
    }

    /**
     * Get the generated slug for this region.
     *
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Cascade slug path updates to child regions recursively.
     */
    public function cascadeSlugPathUpdates(): void
    {
        foreach ($this->children as $child) {
            $child->rebuildSlugPath();
            $child->save();
            $child->cascadeSlugPathUpdates(); // recursive
        }
    }

    /**
     * Clear slug paths of child regions recursively.
     */
    public function clearRelatedSlugPaths(): void
    {
        foreach ($this->children as $child) {
            $child->slug_path = null;
            $child->save();
            $child->clearRelatedSlugPaths(); // recursive
        }
    }

    /**
     * Rebuild and update this region's slug_path.
     */
    public function rebuildSlugPath(): void
    {
        $this->slug_path = $this->buildSlugPath(
            $this->getSlugBase(),
            $this->getSlug()
        );
    }
}
