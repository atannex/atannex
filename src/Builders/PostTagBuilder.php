<?php

namespace Atannex\Builders;

use Atannex\Traits\Bootable;

trait PostTagBuilder
{
    use Bootable;

    /**
     * Get the base string for slug generation.
     *
     * Typically uses the post's category slug path.
     *
     * @return string|null
     */
    public function getSlugBase(): ?string
    {
        return $this->post?->category?->slug_path;
    }

    /**
     * Get the generated slug for this pivot.
     *
     * Typically uses the tag's slug.
     *
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->tag?->slug;
    }

    /**
     * Cascade slug path updates to related entities.
     *
     * Not applicable for pivot models.
     */
    public function cascadeSlugPathUpdates(): void
    {
        // No cascading updates required
    }

    /**
     * Clear slug paths for related entities.
     *
     * Not applicable for pivot models.
     */
    public function clearRelatedSlugPaths(): void
    {
        // No related slug paths to clear
    }
}
