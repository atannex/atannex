<?php

declare(strict_types=1);

namespace Atannex\Traits;

use App\Enums\Flag;
use App\Models\Posts\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

trait HasSlugPath
{
    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id')
            ->where('flag', Flag::PUBLISHED)
            ->whereNull('deleted_at');
    }

    public function generateSlugPath(): string
    {
        if (!$this->parent_id) {
            return $this->slug;
        }

        $parent = $this->relationLoaded('parent')
            ? $this->parent
            : $this->parent()
            ->withoutGlobalScopes()
            ->select(['id', 'slug', 'slug_path'])
            ->first();

        if (!$parent) {
            return $this->slug;
        }

        $base = $parent->slug_path ?: $parent->slug;

        return trim($base . '/' . $this->slug, '/');
    }

    public function updateSlugPathIfNeeded(): void
    {
        $newPath = $this->generateSlugPath();

        if ($this->slug_path !== $newPath) {
            $this->updateQuietly(['slug_path' => $newPath]);
        }
    }

    public function updateDescendantsSlugPaths(): void
    {
        $this->loadMissing('children');

        foreach ($this->children as $child) {
            $child->updateSlugPathIfNeeded();

            $child->posts()->chunkById(100, function ($posts) {
                $posts->each(function (Post $post) {
                    $post->refreshSlugPath();
                    $post->updateQuietly([
                        'slug_path' => $post->slug_path,
                    ]);
                });
            });

            $child->updateDescendantsSlugPaths();
        }
    }

    protected static function bootHasSlugPath(): void
    {
        static::creating(function (Model $model) {
            $model->slug_path = $model->generateSlugPath();
        });

        static::updating(function (Model $model) {
            if ($model->isDirty(['slug', 'parent_id'])) {
                $model->slug_path = $model->generateSlugPath();
            }
        });

        static::updated(function (Model $model) {
            if ($model->wasChanged(['slug', 'parent_id'])) {
                DB::transaction(function () use ($model) {
                    $model->refresh();

                    $model->updateDescendantsSlugPaths();

                    $model->posts()->chunkById(100, function ($posts) {
                        $posts->each(function (Post $post) {
                            $post->refreshSlugPath();
                            $post->updateQuietly([
                                'slug_path' => $post->slug_path,
                            ]);
                        });
                    });
                });
            }
        });
    }
}
