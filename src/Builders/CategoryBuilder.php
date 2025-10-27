<?php

namespace Atannex\Builders;

use App\Models\Posts\Post;
use Atannex\Traits\HasBootable;
use Illuminate\Support\Facades\DB;

trait CategoryBuilder
{
    use HasBootable;

    /**
     * ---------------------------
     * Sluggable Implementation
     * ---------------------------
     */

    /**
     * Get the base string for slug generation.
     *
     * @return string|null
     */
    public function getSlugBase(): ?string
    {
        return $this->parent?->slug_path;
    }

    /**
     * Get the slug value for this category.
     *
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Rebuild this category's slug path.
     *
     * @return void
     */
    public function rebuildSlugPath(): void
    {
        $this->slug_path = $this->buildDynamicSlugPath();
    }

    /**
     * Cascade slug path updates to child categories and related posts.
     *
     * @return void
     */
    public function cascadeSlugPathUpdates(): void
    {
        DB::transaction(function () {
            // 1️⃣ Update child categories recursively
            foreach ($this->children as $child) {
                $child->rebuildSlugPath();
                $child->saveQuietly();

                // Recursive call for deeper hierarchy
                $child->cascadeSlugPathUpdates();
            }

            // 2️⃣ Update related posts (and their tags)
            $this->posts()
                ->with('tags')
                ->get()
                ->each(function (Post $post) {
                    $post->rebuildSlugPath();
                    $post->saveQuietly();
                });
        });
    }

    /**
     * Clear slug paths for child categories and related posts.
     *
     * @return void
     */
    public function clearRelatedSlugPaths(): void
    {
        DB::transaction(function () {
            // 1️⃣ Clear child category slug paths
            foreach ($this->children as $child) {
                $child->updateQuietly(['slug_path' => null]);
                $child->clearRelatedSlugPaths();
            }

            // 2️⃣ Clear related post slug paths
            $this->posts()
                ->get()
                ->each(fn(Post $post) => $post->updateQuietly(['slug_path' => null]));
        });
    }

    /**
     * Boot logic for CategoryBuilder.
     * Can be automatically called by HasBootable trait.
     *
     * @return void
     */
    protected static function bootCategoryBuilder(): void
    {
        static::saving(function ($model) {
            // Automatically rebuild slug before save
            if (method_exists($model, 'buildDynamicSlugPath')) {
                $model->rebuildSlugPath();
            }
        });

        static::saved(function ($model) {
            // Cascade updates after save
            $model->cascadeSlugPathUpdates();
        });

        static::deleting(function ($model) {
            // Clear paths before deletion
            $model->clearRelatedSlugPaths();
        });
    }
}
