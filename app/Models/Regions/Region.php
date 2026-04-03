<?php

declare(strict_types=1);

namespace App\Models\Regions;

use App\Enums\Flag;
use App\Enums\Territories;
use App\Models\Pivots\RegionSectionWidget;
use App\Models\Posts\Post;
use Atannex\Enables\Scoping;
use Atannex\Filters\Hierarchy;
use Atannex\Foundation\Concerns\GeneratesSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use LogicException;

class Region extends Model
{
    use Hierarchy;
    use Scoping;
    use GeneratesSlug;
    use SoftDeletes;

    protected string $slugMode      = self::MODE_WORD;

    protected string $slugColumn    = 'slug';

    protected string|array $slugSource = 'name';

    protected string $slugSeparator = '-';

    protected ?int $slugMaxLength   = 100;

    protected $fillable = [
        'name',
        'flag',
        'slug',
        'territory',
        'description',
        'slug_path',
        'parent_id',
        'position',
    ];

    protected $casts = [
        'flag'      => Flag::class,
        'territory' => Territories::class,
    ];

    protected static function booted(): void
    {
        static::saving(function (self $region) {
            $region->guardAgainstCycles();
            if ($region->shouldRebuildSlugPath()) {
                $region->slug_path = $region->buildSlugPath();
            }
        });

        static::saved(function (self $region) {
            if ($region->wasChanged(['slug', 'parent_id'])) {
                $region->refreshDescendantSlugPaths();
            }
        });

        static::restored(function (self $region) {
            $region->refreshSlugAfterRestore();
            $region->slug_path = $region->buildSlugPath();
            $region->saveQuietly();
            $region->refreshDescendantSlugPaths();
        });
    }

    protected function shouldRebuildSlugPath(): bool
    {
        return $this->isDirty('slug')
            || $this->isDirty('parent_id')
            || empty($this->slug_path);
    }

    public function buildSlugPath(): string
    {
        $segments = [];
        $current = $this;

        while ($current) {
            $segments[] = $current->slug;
            $current = $current->relationLoaded('parent')
                ? $current->parent
                : $current->parent()->first();
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

    protected function guardAgainstCycles(): void
    {
        if (!$this->parent_id || !$this->exists) {
            return;
        }

        if ($this->parent_id === $this->id) {
            throw new LogicException('A region cannot be its own parent.');
        }

        $ancestor = $this->parent;

        while ($ancestor) {
            if ($ancestor->id === $this->id) {
                throw new LogicException('Cyclic hierarchy detected.');
            }

            $ancestor = $ancestor->parent;
        }
    }

    public function getRouteKeyName(): string
    {
        return 'slug_path';
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

    public function ruler(): HasOne
    {
        return $this->hasOne(Ruler::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'region_id');
    }

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'region_section_widgets')
            ->using(RegionSectionWidget::class)
            ->as('pivot')
            ->withPivot(['widget_id', 'position', 'config', 'flag', 'metadata'])
            ->wherePivot('flag', Flag::PUBLISHED)
            ->wherePivotNull('deleted_at')
            ->orderByPivot('position');
    }

    public function widgets(): BelongsToMany
    {
        return $this->belongsToMany(Widget::class, 'region_section_widgets')
            ->using(RegionSectionWidget::class)
            ->as('pivot')
            ->withPivot(['section_id', 'position', 'config', 'flag', 'metadata'])
            ->wherePivot('flag', Flag::PUBLISHED)
            ->wherePivotNull('deleted_at')
            ->orderByPivot('position');
    }
}
