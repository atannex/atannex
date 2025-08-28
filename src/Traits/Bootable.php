<?php

namespace Atannex\Traits;

use App\Contracts\Sluggable;
use Illuminate\Database\Eloquent\Model;

trait Bootable
{
    /**
     * Boot the trait, registering model event listeners.
     */
    protected static function bootBootable(): void
    {
        // Before creating: generate slug_path
        static::creating(function (Model $model) {
            if ($model instanceof Sluggable) {
                $model->slug_path = $model->buildDynamicSlugPath();
            }
        });

        // Before updating: regenerate slug_path if slug or parent changed
        static::updating(function (Model $model) {
            if ($model instanceof Sluggable) {
                $originalSlug = $model->getOriginal('slug');
                $originalParent = $model->getOriginal('parent_id');

                if ($model->slug !== $originalSlug || $model->parent_id !== $originalParent) {
                    $model->slug_path = $model->buildDynamicSlugPath();
                }
            }
        });

        // After save: cascade slug_path updates to children
        static::saved(function (Model $model) {
            if ($model instanceof Sluggable) {
                $model->cascadeSlugPathUpdates();
            }
        });

        // Before delete: clear slug_paths of children
        static::deleting(function (Model $model) {
            if ($model instanceof Sluggable) {
                $model->clearRelatedSlugPaths();
            }
        });
    }

    /**
     * Build a slug path dynamically, using hierarchy if available.
     */
    protected function buildDynamicSlugPath(): ?string
    {
        // Use hierarchical base if parent exists
        $base = method_exists($this, 'getSlugBase') ? $this->getSlugBase() : null;
        $slug = $this->getSlug();

        if ($slug === null) {
            return null;
        }

        return $this->buildSlugPath($base, $slug);
    }

    /**
     * Build a slug path from base and slug.
     */
    protected function buildSlugPath(?string $base, ?string $slug): ?string
    {
        if (!$slug) {
            return null;
        }

        return $base ? rtrim($base, '/') . '/' . $slug : $slug;
    }
}
