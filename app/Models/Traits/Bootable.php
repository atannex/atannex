<?php

namespace App\Models\Traits;

use App\Contracts\Sluggable;

trait Bootable
{
    protected static function boot()
    {
        parent::boot();

        static::saving(fn($model) => $model->updateSlugPath());
        static::saved(fn($model) => $model->cascadeSlugPathUpdates());
        static::deleting(fn($model) => $model->clearRelatedSlugPaths());
    }

    public function updateSlugPath()
    {
        if ($this instanceof Sluggable) {
            $this->slug_path = $this->buildSlugPath(
                $this->getSlugBase(),
                $this->getSlug()
            );
        }
    }

    protected function buildSlugPath(?string $base, ?string $slug): ?string
    {
        return $slug ? ($base ? rtrim($base, '/') . '/' . $slug : $slug) : null;
    }
}

