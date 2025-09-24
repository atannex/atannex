<?php

namespace Atannex\Builders;

use Atannex\Traits\HasBootable;

trait TagBuilder
{
    use HasBootable;

    /**
     * Get the base string for slug_path generation.
     *
     * Typically uses the first post's category slug_path.
     *
     * @return string|null
     */
    public function getSlugBase(): ?string
    {
        $firstPost = $this->posts()->first();
        return $firstPost?->category?->slug_path;
    }

    /**
     * Get the slug to use in slug_path.
     *
     * Relies on HasSlug trait to generate the slug automatically.
     *
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Cascade slug path updates to related entities.
     *
     * Called after saving the model by SluggableObserver.
     */
    public function cascadeSlugPathUpdates(): void
    {
        // No cascading updates required for Tag
    }

    /**
     * Clear slug paths for related entities.
     *
     * Called before deleting the model by SluggableObserver.
     */
    public function clearRelatedSlugPaths(): void
    {
        // No related slug paths to clear for Tag
    }
}
