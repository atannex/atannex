<?php

declare(strict_types=1);

namespace App\Observers;

use Atannex\Contracts\HasImages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ImageObserver
{
    protected string $disk = 'public';

    /**
     * Delete replaced images before saving.
     */
    public function saving(Model&HasImages $model): void
    {
        foreach ($model->images() as $attribute) {
            if (! $model->isDirty($attribute)) {
                continue;
            }

            $old = (array) $model->getOriginal($attribute);
            $new = (array) $model->{$attribute};

            foreach (array_diff($old, $new) as $file) {
                $this->delete($file);
            }
        }
    }

    /**
     * Move temp images to final location after save.
     */
    public function saved(Model&HasImages $model): void
    {
        foreach ($model->images() as $attribute) {
            $files = (array) $model->{$attribute};
            $updated = [];

            foreach ($files as $file) {
                if (! str_contains($file, '/temp/')) {
                    $updated[] = $file;
                    continue;
                }

                $final = str_replace('/temp/', '/' . $model->getKey() . '/', $file);

                if (Storage::disk($this->disk)->exists($file)) {
                    Storage::disk($this->disk)->move($file, $final);
                }

                $updated[] = $final;
            }

            if ($files !== $updated) {
                $model->forceFill([
                    $attribute => is_array($model->{$attribute}) ? $updated : $updated[0],
                ])->saveQuietly();
            }
        }
    }

    /**
     * Delete all images when model is deleted.
     */
    public function deleted(Model&HasImages $model): void
    {
        foreach ($model->images() as $attribute) {
            foreach ((array) $model->{$attribute} as $file) {
                $this->delete($file);
            }
        }
    }

    protected function delete(string $path): void
    {
        if ($path && Storage::disk($this->disk)->exists($path)) {
            Storage::disk($this->disk)->delete($path);
        }
    }
}