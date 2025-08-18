<?php

namespace App\Models\Traits;

use App\Models\Posts\Post;
use App\Models\Pages\Category;
use App\Models\Pivots\PostTag;

trait Bootable
{
    protected static function boot()
    {
        parent::boot();

        static::saving(fn($model) => $model->updateSlugPath());

        static::saved(function ($model) {
            $model->cascadeSlugPathUpdates();
        });

        static::deleting(function ($model) {
            $model->clearRelatedSlugPaths();
        });
    }

    /**
     * Update slug_path safely.
     */
    public function updateSlugPath()
    {
        if ($this instanceof Category) {
            $this->slug_path = $this->buildSlugPath($this->getRelatedSlugPath('parent'), $this->slug);
        } elseif ($this instanceof Post) {
            $this->slug_path = $this->buildSlugPath($this->getRelatedSlugPath('category'), $this->slug);
        } elseif ($this instanceof PostTag) {
            $post = $this->post;
            $tag = $this->tag;
            $categorySlug = $post->category->slug_path ?? null;

            $this->slug_path = $categorySlug
                ? $this->buildSlugPath($categorySlug, $tag->slug ?? null)
                : ($tag->slug ?? null);
        }
    }

    /**
     * Build a slug path safely.
     */
    protected function buildSlugPath(?string $base, ?string $slug): ?string
    {
        return $slug ? ($base ? rtrim($base, '/') . '/' . $slug : $slug) : null;
    }

    /**
     * Get slug_path from related model.
     */
    protected function getRelatedSlugPath(string $relation): ?string
    {
        return $this->$relation->slug_path ?? null;
    }

    /**
     * Cascade slug_path updates to related models.
     */
    protected function cascadeSlugPathUpdates()
    {
        if ($this instanceof Category) {
            $this->posts()->with('tags.tag')->get()->each(fn(Post $post) => $post->cascadeSlugUpdate());
        } elseif ($this instanceof Post) {
            $categorySlug = $this->getRelatedSlugPath('category');
            if ($categorySlug) {
                $this->tags()->with('tag')->get()->each(function (PostTag $postTag) use ($categorySlug) {
                    if ($postTag->tag) {
                        $postTag->slug_path = $this->buildSlugPath($categorySlug, $postTag->tag->slug);
                        $postTag->saveQuietly();
                    }
                });
            }
        }
    }

    /**
     * Helper to cascade slug update for a post.
     */
    protected function cascadeSlugUpdate(): void
    {
        $this->updateSlugPath();
        $this->saveQuietly();
        $this->cascadeSlugPathUpdates();
    }

    /**
     * Clear related slug_paths safely.
     */
    protected function clearRelatedSlugPaths()
    {
        if ($this instanceof Category) {
            $this->posts()->get()->each(fn(Post $post) => $post->clearSlugPath());
        } elseif ($this instanceof Post) {
            $this->tags()->with('tag')->get()->each(fn(PostTag $postTag) => $postTag->update(['slug_path' => null]));
        }
    }

    /**
     * Clear slug path for a single post recursively.
     */
    protected function clearSlugPath(): void
    {
        $this->slug_path = null;
        $this->saveQuietly();
        $this->cascadeSlugPathUpdates();
    }
}
