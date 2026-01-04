<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageObserver
{
    /**
     * Handle the "saving" event.
     * Deletes old images if they are being replaced.
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
     * Handle the "deleted" event.
     * Deletes all associated images.
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
     * Delete a file from storage.
     */
    protected function deleteFile(string $file): void
    {
        if (Storage::disk('public')->exists($file)) {
            Storage::disk('public')->delete($file);
        }
    }
}
