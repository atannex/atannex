<?php

namespace Atannex\Traits;

use Illuminate\Support\Facades\Storage;

trait Cleaning
{
    /**
     * Boot the trait and hook into model lifecycle events.
     */
    public static function bootCleaning(): void
    {
        static::updating(function ($model) {
            $model->deleteOldImagesOnUpdate();
        });

        static::deleting(function ($model) {
            $method = method_exists($model, 'isForceDeleting') && !$model->isForceDeleting()
                ? 'deleteImagesOnSoftDelete'
                : 'deleteImagesOnForceDelete';
            $model->$method();
        });
    }

    /**
     * Get attributes that hold image paths. Override in model to customize.
     *
     * @return array<string>
     */
    protected function imageAttributes(): array
    {
        return ['image'];
    }

    /**
     * Get storage disk for image cleanup. Override in model to customize.
     *
     * @return string
     */
    protected function imageDisk(): string
    {
        return 'public';
    }

    /**
     * Delete images removed during model update.
     */
    protected function deleteOldImagesOnUpdate(): void
    {
        foreach ($this->imageAttributes() as $attribute) {
            if ($this->isDirty($attribute)) {
                $original = $this->ensureArray($this->getOriginal($attribute));
                $current = $this->ensureArray($this->{$attribute});
                $this->deleteImageFiles(array_diff($original, $current));
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

        $this->deleteAllImages();
    }

    /**
     * Delete images on force delete.
     */
    protected function deleteImagesOnForceDelete(): void
    {
        $this->deleteAllImages();
    }

    /**
     * Delete all images for the model.
     */
    protected function deleteAllImages(): void
    {
        foreach ($this->imageAttributes() as $attribute) {
            $this->deleteImageFiles($this->ensureArray($this->{$attribute}));
        }
    }

    /**
     * Delete image files from the storage disk.
     *
     * @param array<string> $paths
     */
    protected function deleteImageFiles(array $paths): void
    {
        $disk = Storage::disk($this->imageDisk());

        foreach ($paths as $path) {
            if ($path && $disk->exists($path)) {
                $disk->delete($path);
            }
        }
    }

    /**
     * Ensure input is an array of non-empty strings.
     *
     * @param mixed $value
     * @return array<string>
     */
    protected function ensureArray(mixed $value): array
    {
        return array_filter(
            is_array($value) ? $value : [],
            fn($item) => is_string($item) && $item !== ''
        );
    }
}