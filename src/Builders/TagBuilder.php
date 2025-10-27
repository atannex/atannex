<?php

namespace Atannex\Builders;

use Atannex\Traits\HasBootable;

trait TagBuilder
{
    use HasBootable;

    /**
     * ---------------------------
     * Sluggable Implementation
     * ---------------------------
     */

    /**
     * Get the base string for slug_path generation.
     *
     * Typically uses the first post's category slug_path.
     *
     * @return string|null
     */
    public function getSlugBase(): ?string
    {
        $firstPost = $this->posts()->with('category')->first();
        return $firstPost?->category?->slug_path;
    }

    /**
     * Get the slug to use in slug_path.
     *
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Rebuild slug_path for the Tag model.
     *
     * @return void
     */
    public function rebuildSlugPath(): void
    {
        if (method_exists($this, 'buildDynamicSlugPath')) {
            $this->slug_path = $this->buildDynamicSlugPath();
        }
    }

    /**
     * Cascade slug path updates to related entities.
     *
     * @return void
     */
    public function cascadeSlugPathUpdates(): void
    {
        // Tag doesn't need recursive updates,
        // but you could hook related updates here if needed later.
    }

    /**
     * Clear slug paths for related entities.
     *
     * @return void
     */
    public function clearRelatedSlugPaths(): void
    {
        // No related entities to clear for tags.
    }

    /**
     * Boot logic for TagBuilder.
     * Automatically integrates with model lifecycle.
     *
     * @return void
     */
    protected static function bootTagBuilder(): void
    {
        static::saving(function ($model) {
            if (method_exists($model, 'buildDynamicSlugPath')) {
                $model->rebuildSlugPath();
            }
        });

        static::saved(function ($model) {
            $model->cascadeSlugPathUpdates();
        });

        static::deleting(function ($model) {
            $model->clearRelatedSlugPaths();
        });
    }
}
