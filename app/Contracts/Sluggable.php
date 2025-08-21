<?php

namespace App\Contracts;

/**
 * Interface for models supporting slug generation and management.
 */
interface Sluggable
{
    /**
     * Get the base string for slug generation.
     *
     * @return string|null The base string for the slug path.
     */
    public function getSlugBase(): ?string;

    /**
     * Get the generated slug for the model.
     *
     * @return string|null The model's slug.
     */
    public function getSlug(): ?string;

    /**
     * Update slug paths for related models or entities.
     */
    public function cascadeSlugPathUpdates(): void;

    /**
     * Clear slug paths for related models or entities.
     */
    public function clearRelatedSlugPaths(): void;
}
