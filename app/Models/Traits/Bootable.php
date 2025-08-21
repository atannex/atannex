<?php

namespace App\Models\Traits;

use App\Contracts\Sluggable;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait Bootable
 *
 * Manages slug path updates for models implementing the Sluggable interface during lifecycle events.
 */
trait Bootable
{
    /**
     * Boot the trait, registering model event listeners for slug path management.
     */
    protected static function bootBootable(): void
    {
        static::saving(function (Model $model) {
            if ($model instanceof Sluggable) {
                $model->slug_path = $model->buildSlugPath(
                    $model->getSlugBase(),
                    $model->getSlug()
                );
            }
        });

        static::saved(function (Model $model) {
            if ($model instanceof Sluggable) {
                $model->cascadeSlugPathUpdates();
            }
        });

        static::deleting(function (Model $model) {
            if ($model instanceof Sluggable) {
                $model->clearRelatedSlugPaths();
            }
        });
    }

    /**
     * Builds a slug path by combining the base and slug.
     *
     * @param string|null $base The base string for the slug path.
     * @param string|null $slug The slug to append to the base.
     * @return string|null The constructed slug path, or null if the slug is empty.
     */
    protected function buildSlugPath(?string $base, ?string $slug): ?string
    {
        if (!$slug) {
            return null;
        }

        return $base ? rtrim($base, '/') . '/' . $slug : $slug;
    }
}
