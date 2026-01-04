<?php

declare(strict_types=1);

namespace App\Models\Regions;

use App\Contracts\Sluggable;
use App\Enums\Flag;
use App\Enums\Territories;
use App\Models\Pivots\PostRegion;
use App\Models\Pivots\RegionSectionWidget;
use App\Models\Posts\Post;
use App\Models\Regions\Ruler;
use Atannex\Enables\Scoping;
use Atannex\Enables\Slugging;
use Atannex\Filters\Hierarchy;
use Atannex\Traits\HasSlugPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model implements Sluggable
{
    use Hierarchy;
    use HasSlugPath;
    use Scoping;
    use Slugging;
    use SoftDeletes;

    protected $table = 'regions';

    protected string $slugSource = 'name';

    protected $fillable = [
        'name',
        'flag',
        'slug',
        'territory',
        'logo',
        'description',
        'slug_path',
        'metadata',
        'parent_id',
    ];

    protected $casts = [
        'flag'      => Flag::class,
        'metadata'  => 'array',
        'territory' => Territories::class,
    ];

    public function images(): array
    {
        return ['logo'];
    }

    public function dir(): string
    {
        return 'regions';
    }

    protected static function booted(): void
    {
        static::bootHasSlugPath();
    }

    public function childrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
    }

    public function ruler(): HasOne
    {
        return $this->hasOne(Ruler::class);
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_region')
            ->using(PostRegion::class)
            ->withPivot(['region_id', 'post_id'])
            ->withTimestamps();
    }

    /**
     * Published sections in this region, ordered by position.
     */
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

    /**
     * Published widgets in this region, ordered by position.
     */
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
