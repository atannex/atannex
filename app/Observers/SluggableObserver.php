<?php

namespace App\Observers;

use App\Contracts\Sluggable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SluggableObserver
 *
 * Observes models implementing the Sluggable interface and handles
 * dynamic slug path generation and cascading updates.
 */
class SluggableObserver
{
    /**
     * Handle the "creating" event.
     *
     * @param Model $model
     * @return void
     */
    public function creating(Model $model): void
    {
        if ($model instanceof Sluggable) {
            $model->slug_path = $model->buildDynamicSlugPath();
        }
    }

    /**
     * Handle the "updating" event.
     *
     * @param Model $model
     * @return void
     */
    public function updating(Model $model): void
    {
        if ($model instanceof Sluggable && ($model->isDirty('slug') || $model->isDirty('parent_id'))) {
            $model->slug_path = $model->buildDynamicSlugPath();
        }
    }

    /**
     * Handle the "saved" event.
     *
     * Cascade slug path updates to related models after saving.
     *
     * @param Model $model
     * @return void
     */
    public function saved(Model $model): void
    {
        if ($model instanceof Sluggable) {
            $model->cascadeSlugPathUpdates();
        }
    }

    /**
     * Handle the "deleting" event.
     *
     * Clear related slug paths to prevent orphaned or invalid paths.
     *
     * @param Model $model
     * @return void
     */
    public function deleting(Model $model): void
    {
        if ($model instanceof Sluggable) {
            $model->clearRelatedSlugPaths();
        }
    }
}
