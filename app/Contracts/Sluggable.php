<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Interface Sluggable
 *
 * Contract for models that manage hierarchical slug paths.
 */
interface Sluggable
{
    /**
     * Generate the complete hierarchical slug path for the model instance.
     *
     * Example: "parent/child/grandchild"
     */
    public function generateSlugPath(): string;

    /**
     * Recursively update the slug paths of all descendant models.
     *
     * Ensures child and nested records reflect updated paths.
     */
    public function updateDescendantsSlugPaths(): void;

    /**
     * Update and persist slug_path if the value has changed.
     */
    public function updateSlugPathIfNeeded(): void;

    /**
     * Get the direct children relationship.
     */
    public function children(): HasMany;

    /**
     * Get the parent relationship.
     */
    public function parent(): BelongsTo;
}
