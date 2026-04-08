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
        'flag' => Flag::class,
        'territory' => Territories::class,
    ];

    /*
    |--------------------------------------------------------------------------
    | BOOT (CLEAN + CONTROLLED)
    |--------------------------------------------------------------------------
    */
    protected static function booted(): void
    {
        static::saving(fn(self $model) => $model->prepareSlugPath());

        static::saved(function (self $model) {
            if ($model->wasChanged(['slug', 'parent_id'])) {
                $model->propagateSlugPathToChildren();
            }
        });

        static::restored(function (self $model) {
            $model->prepareSlugPath();
            $model->saveQuietly();
            $model->propagateSlugPathToChildren();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | SLUG PATH CORE (SINGLE SOURCE OF TRUTH)
    |--------------------------------------------------------------------------
    */

    protected function prepareSlugPath(): void
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

    public function generateSlugPath(): string
    {
        return implode('/', $this->collectSlugSegments());
    }

    /**
     * Centralized traversal (NO duplication anywhere else)
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
    | PROPAGATION (SAFE + CONTROLLED)
    |--------------------------------------------------------------------------
    */

    public function propagateSlugPathToChildren(): void
    {
        $this->loadMissing('children');

        foreach ($this->children as $child) {
            $child->syncSlugPathQuietly();
            $child->propagateSlugPathToChildren();
        }
    }

    protected function syncSlugPathQuietly(): void
    {
        $newPath = $this->generateSlugPath();

        if ($this->slug_path !== $newPath) {
            $this->forceFill(['slug_path' => $newPath])->saveQuietly();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION (UNCHANGED BUT SAFE)
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | ROUTING
    |--------------------------------------------------------------------------
    */

    public function getRouteKeyName(): string
    {
        return 'slug_path';
    }
}
