<?php

namespace App\Models\Pages;

use App\Models\Posts\Post;
use App\Contracts\Sluggable;
use Morfaw\Supports\Resolver;
use App\Models\Traits\Bootable;
use Morfaw\Supports\EnableSlug;
use Morfaw\Supports\EnableScope;
use Atangageih\Filters\GetHierarchy;
use Illuminate\Database\Eloquent\Model;
use Ngangagah\Relations\CategoryRelation;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model implements Sluggable
{
    use SoftDeletes;
    use EnableSlug;
    use CategoryRelation;
    use EnableScope;
    use GetHierarchy;
    use Resolver;
    use Bootable;

    protected string $slugSource = 'name';

    protected $fillable = [
        'name',
        'slug',
        'flag',
        'image',
        'description',
        'published_at',
        'parent_id',
        'slug_path',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function getSlugBase(): string
    {
        return $this->parent->slug_path;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function cascadeSlugPathUpdates(): void
    {
        $this->posts()->with('tags.tag')->get()->each(function (Post $post) {
            $post->updateSlugPath();
            $post->saveQuietly();
            $post->cascadeSlugPathUpdates();
        });
    }

    public function clearRelatedSlugPaths(): void
    {
        $this->posts()->get()->each(function (Post $post) {
            $post->slug_path = null;
            $post->saveQuietly();
            $post->cascadeSlugPathUpdates();
        });
    }
}
