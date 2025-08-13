<?php

namespace App\Models\Traits;

use App\Models\Posts\Post;
use App\Models\Pages\Category;
use App\Models\Pivots\PostTag;

trait Bootable
{
    /**
     * Boot the trait.
     */
    protected static function boot()
    {
        parent::boot();

        // Handle slug_path updates on saving
        static::saving(function ($model) {
            $model->updateSlugPath();
        });

        // Cascade updates to related models after save
        static::saved(function ($model) {
            if ($model instanceof Category) {
                $model->cascadeSlugPathUpdates();
            } elseif ($model instanceof Post) {
                $model->cascadePostTagSlugPathUpdates();
            }
        });

        // Handle slug_path cleanup on delete
        static::deleting(function ($model) {
            if ($model instanceof Category || $model instanceof Post) {
                $model->clearRelatedSlugPaths();
            }
        });
    }

    /**
     * Generate slug_path for the model.
     */
    public function updateSlugPath()
    {
        if ($this instanceof Category) {
            $parentSlug = $this->getRelatedSlugPath('parent');
            $this->slug_path = $parentSlug ? rtrim($parentSlug, '/') . '/' . $this->slug : $this->slug;
        } elseif ($this instanceof Post) {
            $categorySlug = $this->getRelatedSlugPath('category');
            $this->slug_path = $categorySlug ? rtrim($categorySlug, '/') . '/' . $this->slug : null;
        } elseif ($this instanceof PostTag) {
            $this->loadMissing(['post.category', 'tag']);
            $post = $this->post;
            $tag = $this->tag;
            $this->slug_path = ($post && $post->category && $tag)
                ? rtrim($post->category->slug_path, '/') . '/' . $tag->slug
                : ($tag ? $tag->slug : null);
        }
    }

    /**
     * Get slug_path from a related model.
     *
     * @param string $relation
     * @return string|null
     */
    protected function getRelatedSlugPath($relation)
    {
        if ($this->relationLoaded($relation)) {
            return $this->$relation->slug_path ?? null;
        }
        return $this->$relation ? $this->$relation->slug_path : null;
    }

    /**
     * Cascade slug_path updates to related posts and postTags.
     */
    protected function cascadeSlugPathUpdates()
    {
        if ($this instanceof Category) {
            $this->posts()->with('tags.tag')->get()->each(function (Post $post) {
                $post->updateSlugPath();
                $post->saveQuietly();
                $post->cascadePostTagSlugPathUpdates();
            });
        }
    }

    /**
     * Cascade slug_path updates to related postTags.
     */
    protected function cascadePostTagSlugPathUpdates()
    {
        if ($this instanceof Post) {
            $categorySlug = $this->getRelatedSlugPath('category');
            if ($categorySlug) {
                $this->tags()->with('tag')->get()->each(function (PostTag $postTag) use ($categorySlug) {
                    $postTag->slug_path = rtrim($categorySlug, '/') . '/' . $postTag->tag->slug;
                    $postTag->saveQuietly();
                });
            }
        }
    }

    /**
     * Clear slug_path for related models on delete.
     */
    protected function clearRelatedSlugPaths()
    {
        if ($this instanceof Category) {
            $this->posts()->get()->each(function (Post $post) {
                $post->slug_path = null;
                $post->saveQuietly();
                $post->clearRelatedSlugPaths();
            });
        } elseif ($this instanceof Post) {
            $this->tags()->get()->each(function (PostTag $postTag) {
                $postTag->slug_path = null;
                $postTag->saveQuietly();
            });
        }
    }
}
