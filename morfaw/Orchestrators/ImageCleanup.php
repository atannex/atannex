<?php

namespace Morfaw\Orchestrators;

use Illuminate\Support\Facades\Storage;

trait ImageCleanup
{
    /**
     * Boot the trait and hook into model lifecycle events.
     */
    public static function bootImageCleanup(): void
    {
        static::updating(function ($model) {
            $model->deleteOldImagesOnUpdate();
        });

        static::deleting(function ($model) {
            if (method_exists($model, 'isForceDeleting') && !$model->isForceDeleting()) {
                $model->deleteImagesOnSoftDelete();
            } else {
                $model->deleteImagesOnForceDelete();
            }
        });
    }

    /**
     * Attributes that hold image paths. Override in model to customize.
     */
    protected function imageAttributes(): array
    {
        return ['image'];
    }

    /**
     * Storage disk for image cleanup. Override in model to customize.
     */
    protected function imageDisk(): string
    {
        return 'public';
    }

    /**
     * Delete removed images on model update.
     */
    protected function deleteOldImagesOnUpdate(): void
    {
        foreach ($this->imageAttributes() as $attribute) {
            if ($this->isDirty($attribute)) {
                $original = $this->getOriginal($attribute);
                $current = $this->{$attribute};

                $originalPaths = $this->ensureArray($original);
                $currentPaths = $this->ensureArray($current);

                $removedPaths = array_diff($originalPaths, $currentPaths);
                $this->deleteImageFile($removedPaths);
            }
        }
    }

    /**
     * Delete images on soft delete if configured.
     */
    protected function deleteImagesOnSoftDelete(): void
    {
        if (property_exists($this, 'deleteImageOnSoftDelete') && !$this->deleteImageOnSoftDelete) {
            return;
        }

        $this->deleteImages();
    }

    /**
     * Delete images on force delete.
     */
    protected function deleteImagesOnForceDelete(): void
    {
        $this->deleteImages();
    }

    /**
     * Delete all images for the model.
     */
    protected function deleteImages(): void
    {
        foreach ($this->imageAttributes() as $attribute) {
            $this->deleteImageFile($this->{$attribute});
        }
    }

    /**
     * Delete image file(s) from disk.
     */
    protected function deleteImageFile(array|null $paths): void
    {
        $disk = Storage::disk($this->imageDisk());

        foreach ($this->ensureArray($paths) as $path) {
            if ($path && $disk->exists($path)) {
                $disk->delete($path);
            }
        }
    }

    /**
     * Ensure input is an array of strings (no JSON parsing allowed).
     */
    protected function ensureArray(mixed $value): array
    {
        return array_filter(
            is_array($value) ? $value : [],
            fn($item) => is_string($item) && $item !== ''
        );
    }
}
