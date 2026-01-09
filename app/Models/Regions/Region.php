<?php

declare(strict_types=1);

namespace App\Models\Regions;

use App\Enums\Flag;
use App\Enums\Territories;
use App\Models\Posts\Post;
use App\Contracts\Sluggable;
use Atannex\Enables\Scoping;
use App\Models\Regions\Ruler;
use Atannex\Enables\Slugging;
use Atannex\Filters\Hierarchy;
use Atannex\Traits\HasSlugPath;
use App\Models\Pivots\PostRegion;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pivots\RegionSectionWidget;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
