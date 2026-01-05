<?php

declare(strict_types=1);

namespace App\Observers;

use Atannex\Contracts\HasImages;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Image Observer
|--------------------------------------------------------------------------
|
| Manages image lifecycle for any model implementing HasImages.
| Responsibilities:
|  - Deletes replaced images before save
|  - Moves files from temp storage to final directories post-save
|  - Cleans up all associated files when the model is deleted
|
| Key Notes:
| - Intersection type Model&HasImages ensures:
|     1. Eloquent functionality (isDirty, getOriginal)
|     2. HasImages contract (images(), dir())
| - Compatible with Filament Admin, API uploads, and native Eloquent workflows
| - Supports single or multiple image attributes (arrays/JSON)
| - $disk property configurable; default is 'public'
|
| Best Practices:
| - Models must implement HasImages to opt-in
| - image attributes must return **attribute names**, not file paths
| - Observer is universal and can handle temp-to-final migration for new records
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
     * Deletes replaced images before the model is saved.
     *
     * @param Model&HasImages $model
     */
    public function saving(Model&HasImages $model): void
    {
        foreach ($model->images() as $attribute) {
            if (! $model->isDirty($attribute)) {
                continue;
            }

            $original = $model->getOriginal($attribute);
            $current  = $model->{$attribute};

            // Handle arrays (JSON columns) or single file paths
            $originalFiles = is_array($original) ? $original : [$original];
            $currentFiles  = is_array($current) ? $current : [$current];

            foreach ($originalFiles as $file) {
                if ($file !== '' && ! in_array($file, $currentFiles, true)) {
                    $this->delete((string) $file);
                }
            }
        }
    }

    /**
     * Handle the "saved" event.
     * Moves temp files to final directories and updates model attributes.
     *
     * @param Model&HasImages $model
     */
    public function saved(Model&HasImages $model): void
    {
        foreach ($model->images() as $attribute) {
            $value = $model->{$attribute};

            // Skip if empty
            if ($value === '' || $value === null) {
                continue;
            }

            // Handle multiple images stored in array
            $files = is_array($value) ? $value : [$value];
            $updatedFiles = [];

            foreach ($files as $file) {
                if (! str_contains($file, '/temp/')) {
                    $updatedFiles[] = $file;
                    continue; // Already in final directory
                }

                $finalPath = str_replace('/temp/', '/' . $model->getKey() . '/', $file);

                if (! Storage::disk($this->disk)->exists($file)) {
                    Log::warning(
                        "ImageObserver: Temp file does not exist: {$file}",
                        ['model' => get_class($model), 'id' => $model->getKey()]
                    );
                    $updatedFiles[] = $file;
                    continue;
                }

                $success = Storage::disk($this->disk)->move($file, $finalPath);

                if ($success) {
                    $updatedFiles[] = $finalPath;
                } else {
                    Log::warning(
                        "ImageObserver: Failed to move file from {$file} to {$finalPath}",
                        ['model' => get_class($model), 'id' => $model->getKey()]
                    );
                    $updatedFiles[] = $file; // Keep original path to avoid losing reference
                }
            }

            // Save updated paths quietly
            $model->forceFill([$attribute => is_array($value) ? $updatedFiles : $updatedFiles[0]])
                ->saveQuietly();
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
            $value = $model->{$attribute};

            if ($value === '' || $value === null) {
                continue;
            }

            $files = is_array($value) ? $value : [$value];

            foreach ($files as $file) {
                $this->delete((string) $file);
            }
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
