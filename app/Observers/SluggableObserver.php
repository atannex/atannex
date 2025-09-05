<?php

namespace App\Observers;

use App\Contracts\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SluggableObserver
{
    /**
     * Handle the "creating" event.
     *
     * @param Model|Pivot&Sluggable $model
     */
    public function creating(Model|Pivot $model): void
    {
        $this->updateSlugPath($model);
    }

    /**
     * Handle the "updating" event.
     *
     * @param Model|Pivot&Sluggable $model
     */
    public function updating(Model|Pivot $model): void
    {
        if ($model instanceof Model &&
            ($model->isDirty('slug') || $model->isDirty('parent_id'))) {
            $this->updateSlugPath($model);
        }
    }

    /**
     * Handle the "saved" event.
     *
     * @param Model|Pivot&Sluggable $model
     */
    public function saved(Model|Pivot $model): void
    {
        $model->cascadeSlugPathUpdates();
    }

    /**
     * Handle the "deleting" event.
     *
     * @param Model|Pivot&Sluggable $model
     */
    public function deleting(Model|Pivot $model): void
    {
        $model->clearRelatedSlugPaths();
    }

    /**
     * Update the slug path for the model.
     *
     * @param Model|Pivot&Sluggable $model
     */
    private function updateSlugPath(Model|Pivot $model): void
    {
        if ($model instanceof Sluggable) {
            $model->slug_path = $model->buildDynamicSlugPath();
        }
    }
}
