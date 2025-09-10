<?php

namespace Atannex\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Trait HasCleaning
 *
 * Provides functionality for handling image uploads and automatic cleanup of old images
 * when updating or deleting models.
 */
trait HasCleaning
{
    /**
     * Get the name of the image attribute.
     *
     * @return string
     */
    abstract public function getImageAttributeName(): string;

    /**
     * Get the storage directory for the image.
     *
     * @return string
     */
    abstract public function getImageDirectory(): string;

    /**
     * Set the image attribute, storing uploaded files and updating the attribute value.
     *
     * @param UploadedFile|string|null $value The uploaded file or path string
     * @return void
     */
    public function setImageAttribute($value): void
    {
        $attribute = $this->getImageAttributeName();
        $this->attributes[$attribute] = $value instanceof UploadedFile
            ? $value->store($this->getImageDirectory(), 'public')
            : $value;
    }

    /**
     * Delete an image file from storage.
     *
     * @param string|null $file The file path to delete (defaults to the model's current image)
     * @return void
     */
    public function deleteImage(?string $file = null): void
    {
        $fileToDelete = $file ?? $this->getOriginal($this->getImageAttributeName());

        if ($fileToDelete && Storage::disk('public')->exists($fileToDelete)) {
            Storage::disk('public')->delete($fileToDelete);
        }
    }

    /**
     * Boot the trait, setting up event listeners for image cleanup.
     *
     * @return void
     */
    protected static function bootHasCleaning(): void
    {
        // Clean up old image when updating
        static::saving(function ($model) {
            $attribute = $model->getImageAttributeName();
            if ($model->isDirty($attribute)) {
                $model->deleteImage($model->getOriginal($attribute));
            }
        });

        // Clean up image on soft or force delete
        static::deleted(fn($model) => $model->deleteImage());

        static::forceDeleted(fn($model) => $model->deleteImage());

    }
}
