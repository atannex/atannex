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
     * For hierarchical models, this usually returns the parent model's slug path.
     *
     * @return string|null The base path to prepend to the model's slug, or null if none.
     */
    public function getSlugBase(): ?string;

    /**
     * Get the model's own slug segment.
     *
     * This represents the unique slug for the current model.
     *
     * @return string|null The slug segment, or null if not generated yet.
     */
    public function getSlug(): ?string;

    /**
     * Cascade slug path updates to related models or entities.
     *
     * Should be invoked after saving the model to propagate slug changes to children
     * or dependent entities.
     *
     * @return void
     */
    public function cascadeSlugPathUpdates(): void;

    /**
     * Clear slug paths for related models or entities.
     *
     * Typically called before deleting the model to avoid orphaned or invalid paths.
     *
     * @return void
     */
    public function clearRelatedSlugPaths(): void;
}
