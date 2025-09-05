<?php

namespace App\Contracts;

/**
 * Interface Sluggable
 *
 * Defines the contract for models that support dynamic slug generation
 * and hierarchical slug paths.
 */
interface Sluggable
{
    /**
     * Get the base string used for slug generation.
     *
     * @return string|null
     */
    public function getSlugBase(): ?string;

    /**
     * Get the model's own slug segment.
     *
     * @return string|null
     */
    public function getSlug(): ?string;

    /**
     * Build the full slug path for the model.
     *
     * @return string|null
     */
    public function buildDynamicSlugPath(): ?string;

    /**
     * Cascade slug path updates to related models.
     *
     * @return void
     */
    public function cascadeSlugPathUpdates(): void;

    /**
     * Clear slug paths for related models.
     *
     * @return void
     */
    public function clearRelatedSlugPaths(): void;
}
