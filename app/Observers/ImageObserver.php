<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageObserver
{
    /**
     * Handle model saving by replacing changed image attributes:
     * delete the previous file and store any UploadedFile, updating the attribute with the stored path.
     *
     * @param Model $model The model instance being saved.
     */
    public function saving(Model $model): void
    {
        foreach ($model->images() as $attr) {
            if ($model->isDirty($attr)) {
                $old = $model->getOriginal($attr);
                if ($old) {
                    $this->deleteFile($old);
                }

                $new = $model->$attr;
                if ($new instanceof UploadedFile) {
                    $model->$attr = $new->store($model->dir, 'public');
                }
            }
        }
    }

    /**
     * Remove all image files referenced by the model when the model is deleted.
     *
     * For each attribute name returned by $model->images(), deletes the stored file if the attribute contains a path.
     *
     * @param \Illuminate\Database\Eloquent\Model $model The model instance being deleted.
     */
    public function deleted(Model $model): void
    {
        foreach ($model->images() as $attr) {
            $file = $model->$attr;
            if ($file) {
                $this->deleteFile($file);
            }
        }
    }

    /**
     * Remove a file from the public storage disk if it exists.
     *
     * @param string $file The file path relative to the public disk.
     */
    protected function deleteFile(string $file): void
    {
        if (Storage::disk('public')->exists($file)) {
            Storage::disk('public')->delete($file);
        }
    }
}