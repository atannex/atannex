<?php

declare(strict_types=1);

namespace Atannex\Traits;

use App\Models\Posts\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasSlugPath
{
    /**
     * Define the parent relationship.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    /**
     * Define the children relationship.
     */
    public function children(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    /**
     * Generate the full hierarchical slug path.
     */
    public function generateSlugPath(): string
    {
        $segments = [];
        $current = $this;

        while ($current) {
            $segments[] = $current->slug;
            $current = $current->parent()
                ->withoutGlobalScopes()
                ->select(['id', 'parent_id', 'slug'])
                ->first();
        }

        return implode('/', array_reverse($segments));
    }

    /**
     * Recursively update all descendant slug paths.
     */
    public function updateDescendantsSlugPaths(): void
    {
        $this->loadMissing('children');

        foreach ($this->children as $child) {
            $child->updateSlugPathIfNeeded();

            $child->posts()->chunk(100, function ($posts) {
                $posts->each(function (Post $post) {
                    $post->refreshSlugPath();
                    $post->updateQuietly(['slug_path' => $post->slug_path]);
                });
            });

            $child->updateDescendantsSlugPaths();
        }
    }

    /**
     * Set and persist slug_path if it's different from the generated one.
     */
    public function updateSlugPathIfNeeded(): void
    {
        $newPath = $this->generateSlugPath();

        if ($this->slug_path !== $newPath) {
            $this->updateQuietly(['slug_path' => $newPath]);
        }
    }

    /**
     * Boot the trait and handle automatic slug_path updates.
     */
    protected static function bootHasSlugPath(): void
    {
        static::saving(function (Model $model) {
            if ($model->isDirty(['slug', 'parent_id'])) {
                $model->slug_path = $model->generateSlugPath();
            }
        });

        static::saved(function (Model $model) {
            if ($model->wasChanged(['slug', 'parent_id'])) {
                DB::transaction(function () use ($model) {

                    $model->refresh();

                    $model->updateSlugPathIfNeeded();

                    $model->updateDescendantsSlugPaths();

                    $model->posts()->get()->each(function ($post) {
                        $post->refreshSlugPath();
                        $post->updateQuietly(['slug_path' => $post->slug_path]);
                    });
                });
            }
        });
    }
}
