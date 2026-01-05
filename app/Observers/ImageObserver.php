<?php

declare(strict_types=1);

namespace App\Observers;

use Atannex\Contracts\HasImages;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ImageObserver
{
    protected string $disk = 'public';

    /**
     * Deletes replaced images before saving.
     */
    public function saving(Model&HasImages $model): void
    {
        foreach ($model->images() as $attribute) {
            if (! $model->isDirty($attribute)) {
                continue;
            }

            $originalFiles = (array) $model->getOriginal($attribute);
            $currentFiles  = (array) $model->{$attribute};

            foreach ($originalFiles as $file) {
                if ($file !== '' && ! in_array($file, $currentFiles, true)) {
                    $this->delete($file);
                }
            }
        }
    }

    /**
     * Moves all temp files to final directories post-save, atomically with DB.
     */
    public function saved(Model&HasImages $model): void
    {
        $movedFiles = []; // Track successful file moves for rollback

        try {
            DB::transaction(function () use ($model, &$movedFiles) {
                $changes = [];

                // Step 1: Prepare mapping of old => new paths
                foreach ($model->images() as $attribute) {
                    $value = $model->{$attribute};
                    if (empty($value)) {
                        continue;
                    }

                    $files = (array) $value;
                    $updatedFiles = [];

                    foreach ($files as $file) {
                        if (! str_contains($file, '/temp/')) {
                            $updatedFiles[] = $file;
                            continue;
                        }

                        $finalPath = str_replace('/temp/', '/' . $model->getKey() . '/', $file);

                        if (! Storage::disk($this->disk)->exists($file)) {
                            Log::warning("ImageObserver: Temp file missing: {$file}", [
                                'model' => get_class($model),
                                'id'    => $model->getKey(),
                            ]);
                            $updatedFiles[] = $file;
                            continue;
                        }

                        $updatedFiles[] = $finalPath;
                    }

                    if ($files !== $updatedFiles) {
                        $changes[$attribute] = $updatedFiles;
                    }
                }

                if (empty($changes)) {
                    return;
                }

                // Step 2: Move all temp files and track them for rollback
                foreach ($changes as $attribute => $updated) {
                    $originalFiles = (array) $model->{$attribute};

                    foreach ($originalFiles as $index => $file) {
                        $finalPath = $updated[$index] ?? $file;

                        if ($file === $finalPath) {
                            continue;
                        }

                        Storage::disk($this->disk)->move($file, $finalPath);
                        $movedFiles[$finalPath] = $file; // Track new => old for rollback
                    }
                }

                // Step 3: Update model attributes
                foreach ($changes as $attribute => $updated) {
                    $model->forceFill([$attribute => is_array($model->{$attribute}) ? $updated : $updated[0]])
                        ->saveQuietly();
                }
            }, 3); // Retry up to 3 times on deadlocks
        } catch (\Exception $e) {
            // Rollback any moved files
            foreach ($movedFiles as $new => $original) {
                if (Storage::disk($this->disk)->exists($new)) {
                    Storage::disk($this->disk)->move($new, $original);
                }
            }

            Log::error("ImageObserver: Transaction failed and files rolled back", [
                'model' => get_class($model),
                'id'    => $model->getKey(),
                'error' => $e->getMessage(),
            ]);

            throw $e; // Re-throw so the error can be handled upstream
        }
    }

    /**
     * Deletes all images when the model is deleted.
     */
    public function deleted(Model&HasImages $model): void
    {
        foreach ($model->images() as $attribute) {
            $files = (array) $model->{$attribute};
            foreach ($files as $file) {
                $this->delete($file);
            }
        }
    }

    /**
     * Delete a file from storage if it exists.
     */
    protected function delete(string $path): void
    {
        if ($path !== '' && Storage::disk($this->disk)->exists($path)) {
            Storage::disk($this->disk)->delete($path);
        }
    }
}
