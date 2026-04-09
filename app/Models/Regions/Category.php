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

    protected string $slugMode = self::MODE_WORD;

    protected string $slugColumn = 'slug';

    protected string|array $slugSource = 'name';

    protected string $slugSeparator = '-';

    protected ?int $slugMaxLength = 100;

    /*
    |--------------------------------------------------------------------------
    | BOOT
    |--------------------------------------------------------------------------
    */
    protected static function booted(): void
    {
        static::saving(fn(self $model) => $model->syncSlugPathIfNeeded());

        static::saved(function (self $model) {
            if ($model->wasChanged(['slug', 'parent_id'])) {
                $model->dispatchSlugPropagation();
            }
        });

        static::restored(function (self $model) {
            $model->syncSlugPathIfNeeded();
            $model->dispatchSlugPropagation();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | CORE LOGIC (SINGLE RESPONSIBILITY)
    |--------------------------------------------------------------------------
    */

    protected function syncSlugPathIfNeeded(): void
    {
        if (!$this->shouldRebuildSlugPath()) {
            return;
        }

        $this->slug_path = $this->generateSlugPath();
    }

    protected function shouldRebuildSlugPath(): bool
    {
        return $this->isDirty(['slug', 'parent_id']) || blank($this->slug_path);
    }

    /**
     * Pure function: builds hierarchy path without side effects
     */
    public function generateSlugPath(): string
    {
        $segments = $this->collectSlugSegments();

        return implode('/', $segments);
    }

    /**
     * Centralized hierarchy traversal (no duplication anywhere else)
     */
    protected function collectSlugSegments(): array
    {
        $segments = [];
        $node = $this;
        $guard = 0;

        while ($node && $guard < 50) {
            $segments[] = $node->slug;
            $node = $node->parent;
            $guard++;
        }

        return array_reverse($segments);
    }

    /*
    |--------------------------------------------------------------------------
    | DESCENDANT PROPAGATION (SAFE + CONTROLLED)
    |--------------------------------------------------------------------------
    */

    public function dispatchSlugPropagation(): void
    {
        $this->loadMissing('children');

        foreach ($this->children as $child) {
            $child->updateSlugPathQuietly();
            $child->dispatchSlugPropagation();
        }
    }

    protected function updateSlugPathQuietly(): void
    {
        $newPath = $this->generateSlugPath();

        if ($this->slug_path !== $newPath) {
            $this->forceFill(['slug_path' => $newPath])->saveQuietly();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

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

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'category_section')
            ->using(CategorySection::class)
            ->withPivot('flag')
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | ROUTING
    |--------------------------------------------------------------------------
    */

    public function getRouteKeyName(): string
    {
        return 'slug_path';
    }

    public function getUrlAttribute(): string
    {
        return url('categories/' . $this->slug_path);
    }
}
