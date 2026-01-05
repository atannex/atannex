<?php

declare(strict_types=1);

namespace App\Observers;

use Atannex\Contracts\HasImages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Image Observer
|--------------------------------------------------------------------------
|
| Handles image lifecycle for any model implementing the HasImages contract.
| Responsibilities include:
|  - Deleting old images when replaced
|  - Moving temporary uploads to final directories after model creation
|  - Cleaning up images when a model is deleted
|
| Design Notes:
| - Fully compatible with Filament Admin, API uploads, and standard Eloquent
|   workflows.
| - Uses intersection type Model&HasImages to enforce:
|       1. Model is an Eloquent instance (provides isDirty, getOriginal, etc.)
|       2. Model implements HasImages contract (provides images() and dir())
| - Disk can be configured via $disk property. Default is 'public'.
|
| File Handling:
| - On saving: deletes any old file if a new path is set
| - On saved: moves files from 'temp' to final directories post-save
| - On deleted: removes all associated files
|
| Best Practices:
| - Models opting in must implement HasImages
| - Works for single or multiple image attributes
| - Ensures no orphaned files remain
*/

class ImageObserver
{
    /**
     * Storage disk used for image operations.
     *
     * @var string
     */
    protected string $disk = 'public';

    /**
     * Handle the "saving" event.
     * Deletes replaced images before model is saved.
     *
     * @param Model&HasImages $model
     */
    public function saving(Model&HasImages $model): void
    {
        foreach ($model->images() as $attribute) {
            if (! $model->isDirty($attribute)) {
                continue;
            }

            $original = (string) $model->getOriginal($attribute);
            $current  = (string) $model->{$attribute};

            // Only delete if the old path exists and differs from the new
            if ($original !== '' && $original !== $current) {
                $this->delete($original);
            }
        }
    }

    /**
     * Handle the "saved" event.
     * Moves files from temporary storage to the final model directory.
     *
     * @param Model&HasImages $model
     */
    public function saved(Model&HasImages $model): void
    {
        foreach ($model->images() as $attribute) {
            $path = (string) $model->{$attribute};

            // Skip if empty or already in final directory
            if ($path === '' || ! str_contains($path, '/temp/')) {
                continue;
            }

            // Compute final path using model ID
            $finalPath = str_replace(
                '/temp/',
                '/' . $model->getKey() . '/',
                $path
            );

            // Move file on disk and update model without triggering events
            if (Storage::disk($this->disk)->move($path, $finalPath)) {
                $model->forceFill([$attribute => $finalPath])
                    ->saveQuietly();
            }
        }
    }

    /**
     * Handle the "deleted" event.
     * Deletes all images associated with the model.
     *
     * @param Model&HasImages $model
     */
    public function deleted(Model&HasImages $model): void
    {
        foreach ($model->images() as $attribute) {
            $this->delete((string) $model->{$attribute});
        }
    }

    /**
     * Delete a file from storage if it exists.
     *
     * @param string $path
     */
    protected function delete(string $path): void
    {
        if ($path !== '' && Storage::disk($this->disk)->exists($path)) {
            Storage::disk($this->disk)->delete($path);
        }
    }
}
