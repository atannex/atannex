<?php

namespace Atannex\Builders;

use App\Models\Posts\Post;
use Atannex\Traits\Bootable;

trait CategoryBuilder
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
     * @return string|null The parent category's slug path, or null if no parent.
     */
    public function getSlugBase(): ?string
    {
        return $this->parent?->slug_path;
    }

    /**
     * Get the generated slug for this category.
     *
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Rebuild this category's slug path.
     */
    public function rebuildSlugPath(): void
    {
        $this->slug_path = $this->buildDynamicSlugPath();
    }

    /**
     * Cascade slug path updates to child categories and related posts recursively.
     */
    public function cascadeSlugPathUpdates(): void
    {
        // Update child categories recursively
        foreach ($this->children as $child) {
            $child->rebuildSlugPath();
            $child->saveQuietly();
            $child->cascadeSlugPathUpdates();
        }

        // Update related posts with their tags
        $this->posts()->with('tags')->get()->each(function (Post $post) {
            $post->rebuildSlugPath();
            $post->saveQuietly();
        });
    }

    /**
     * Clear slug paths for children categories and related posts recursively.
     */
    public function clearRelatedSlugPaths(): void
    {
        // Clear child categories recursively
        foreach ($this->children as $child) {
            $child->slug_path = null;
            $child->saveQuietly();
            $child->clearRelatedSlugPaths();
        }

        // Clear related posts
        $this->posts()->get()->each(function (Post $post) {
            $post->slug_path = null;
            $post->saveQuietly();
        });
    }
}
