<?php

declare(strict_types=1);

namespace App\Models\Regions;

use App\Enums\Flag;
use App\Models\Pivots\CategorySection;
use App\Models\Posts\Post;
use App\Models\Posts\Video;
use Atannex\Enables\Scoping;
use Atannex\Filters\Hierarchy;
use Atannex\Foundation\Concerns\GeneratesSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use Hierarchy;
    use Scoping;
    use SoftDeletes;
    use GeneratesSlug;

    protected string $slugMode      = self::MODE_WORD;

    protected string $slugColumn    = 'slug';

    protected string|array $slugSource = 'name';

    protected string $slugSeparator = '-';

    protected ?int $slugMaxLength   = 100;

    protected $fillable = [
        'name',
        'slug',
        'flag',
        'image',
        'description',
        'parent_id',
        'slug_path',
    ];

    protected $casts = [
        'flag' => Flag::class,
    ];

    protected function shouldRebuildSlugPath(): bool
    {
        return $this->isDirty('slug')
            || $this->isDirty('parent_id')
            || empty($this->slug_path);
    }

    public function buildSlugPath(): string
    {
        if (!$this->slug) {
            $this->regenerateSlug();
        }

        $segments = [$this->slug];
        $parent = $this->parent;

        $maxDepth = 20;
        $depth = 0;

        while ($parent && $depth < $maxDepth) {
            $segments[] = $parent->slug;
            $parent = $parent->relationLoaded('parent')
                ? $parent->parent
                : $parent->parent()->first();

            $depth++;
        }

        return implode('/', array_reverse($segments));
    }

    public function refreshDescendantSlugPaths(): void
    {
        $this->loadMissing('children');

        foreach ($this->children as $child) {
            $newPath = $child->buildSlugPath();

            if ($child->slug_path !== $newPath) {
                $child->slug_path = $newPath;
                $child->saveQuietly();
            }

            $child->refreshDescendantSlugPaths();
        }
    }

    public function getRouteKeyName(): string
    {
        return 'slug_path';
    }

    protected static function booted(): void
    {
        static::saving(function (self $category) {
            if ($category->shouldRebuildSlugPath()) {
                $category->slug_path = $category->buildSlugPath();
            }
        });

        static::saved(function (self $category) {
            if ($category->wasChanged(['slug', 'parent_id'])) {
                $category->refreshDescendantSlugPaths();
            }
        });

        static::restored(function (self $category) {
            $category->refreshSlugAfterRestore();

            if ($category->shouldRebuildSlugPath()) {
                $category->slug_path = $category->buildSlugPath();
                $category->saveQuietly();
            }

            $category->refreshDescendantSlugPaths();
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'category_section')
            ->using(CategorySection::class)
            ->withPivot('flag')
            ->withTimestamps();
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }
}
