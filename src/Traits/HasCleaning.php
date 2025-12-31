<?php

namespace Atannex\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Trait HasCleaning
 *
 * Automatically handles image uploads and cleanup on update or deletion.
 * Fully universal, supports multiple images per model.
 */
trait HasCleaning
{
    /**
     * Return the list of image attributes for this model.
     * Override in your model if there are multiple fields.
     */
    public function images(): array
    {
        return ['image']; // default single image
    }

    /**
     * Return the storage directory for images.
     * Override in your model if needed.
     */
    public function dir(): string
    {
        return 'images';
    }

    /**
     * Set an image attribute, handling file uploads.
     *
     * @param string $attr
     * @param UploadedFile|string|null $value
     */
    protected function setImage(string $attr, $value): void
    {
        $this->attributes[$attr] = $value instanceof UploadedFile
            ? $value->store($this->dir(), 'public')
            : $value;
    }

    /**
     * Delete an image file from storage.
     *
     * @param string|null $file
     */
    public function deleteImage(?string $file = null): void
    {
        if ($file && Storage::disk('public')->exists($file)) {
            Storage::disk('public')->delete($file);
        }
    }

    /**
     * Boot the trait, attaching events for cleanup.
     */
    protected static function bootHasCleaning(): void
    {
        // Remove old images on update
        static::saving(function ($model) {
            foreach ($model->images() as $attr) {
                if ($model->isDirty($attr)) {
                    $model->deleteImage($model->getOriginal($attr));
                }
            }
        });

        // Remove images on any deletion
        static::deleted(function ($model) {
            foreach ($model->images() as $attr) {
                $model->deleteImage($model->$attr);
            }
        });

        static::forceDeleted(function ($model) {
            foreach ($model->images() as $attr) {
                $model->deleteImage($model->$attr);
            }
        });
    }
}
