<?php

namespace App\Contracts;

/**
 * Interface for models that support slug generation and management.
 */
interface Sluggable
{
    /**
     * Retrieves the base string used for generating the slug.
     *
     * @return string The base string for slug creation.
     */
    public function getSlugBase(): string;

    /**
     * Retrieves the generated slug for the model.
     *
     * @return string The generated slug.
     */
    public function getSlug(): string;

    /**
     * Updates the slug paths for related models or entities.
     *
     * @return void
     */
    public function cascadeSlugPathUpdates(): void;

    /**
     * Clears the slug paths for related models or entities.
     *
     * @return void
     */
    public function clearRelatedSlugPaths(): void;
}
