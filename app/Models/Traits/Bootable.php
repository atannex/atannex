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
        static::saved(fn($model) => $model->cascadeSlugPathUpdates());
        static::deleting(fn($model) => $model->clearRelatedSlugPaths());
    }

    public function updateSlugPath()
    {
        $slug = $this->slug ?? null;
        $base = null;

        if ($this instanceof Category) {
            $base = $this->getRelatedSlugPath('parent');
        } elseif ($this instanceof Post) {
            $base = $this->getRelatedSlugPath('category');
        } elseif ($this instanceof PostTag) {
            $post = $this->post;
            $base = $post->category->slug_path ?? null;
            $slug = $this->tag->slug ?? null;
        }

        $this->slug_path = $this->buildSlugPath($base, $slug);
    }

    protected function buildSlugPath(?string $base, ?string $slug): ?string
    {
        return $slug ? ($base ? rtrim($base, '/') . '/' . $slug : $slug) : null;
    }

    protected function getRelatedSlugPath(string $relation): ?string
    {
        return $this->$relation->slug_path ?? null;
    }

    protected function cascadeSlugPathUpdates()
    {
        if ($this instanceof Category) {
            $this->posts()->with('tags.tag')->get()->each(function (Post $post) {
                $post->updateSlugPath();
                $post->saveQuietly();
                $post->cascadeSlugPathUpdates();
            });
        } elseif ($this instanceof Post) {
            $categorySlug = $this->getRelatedSlugPath('category');
            if ($categorySlug) {
                PostTag::where('post_id', $this->id)
                    ->with('tag')
                    ->get()
                    ->each(function (PostTag $postTag) use ($categorySlug) {
                        if ($postTag->tag) {
                            $postTag->slug_path = $this->buildSlugPath($categorySlug, $postTag->tag->slug);
                            $postTag->saveQuietly();
                        }
                    });
            }
        }
    }

    protected function clearRelatedSlugPaths()
    {
        if ($this instanceof Category) {
            $this->posts()->get()->each(function (Post $post) {
                $post->slug_path = null;
                $post->saveQuietly();
                $post->cascadeSlugPathUpdates();
            });
        } elseif ($this instanceof Post) {
            $this->tags()->update(['slug_path' => null]);
        }
    }
}
