<?php

namespace App\Models\Traits;

use App\Contracts\Sluggable;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait Bootable
 *
 * Provides bootable functionality for models implementing the Sluggable interface,
 * handling slug path updates during model lifecycle events.
 */
trait Bootable
{
    /**
     * Boot the trait, registering model event listeners for slug path management.
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (Model $model): void {
            if ($model instanceof Sluggable) {
                $model->updateSlugPath();
            }
        });

        static::saved(function (Model $model): void {
            if ($model instanceof Sluggable) {
                $model->cascadeSlugPathUpdates();
            }
        });

        static::deleting(function (Model $model): void {
            if ($model instanceof Sluggable) {
                $model->clearRelatedSlugPaths();
            }
        });
    }

    /**
     * Updates the slug path for the model based on its slug base and slug.
     *
     * @return void
     */
    public function updateSlugPath(): void
    {
        $this->slug_path = $this->buildSlugPath(
            $this->getSlugBase(),
            $this->getSlug()
        );
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
