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
use Illuminate\Database\Eloquent\Model;
use App\Models\Pivots\RegionSectionWidget;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Region extends Model implements Sluggable
{
    use Hierarchy;
    use Slugging;
    use HasSlugPath;
    use Scoping;
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

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::bootHasSlugPath();
    }

    /**
     * Gets the relationship for this region's child regions and their descendants.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany The has-many relationship for this region's direct children; descendants are eager-loaded recursively.
     */
    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    /**
     * Get the region's ruler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne The one-to-one relationship to the Ruler model.
     */
    public function ruler(): HasOne
    {
        return $this->hasOne(Ruler::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'region_id');
    }

    /**
     * Get this region's published sections ordered by the pivot `position`.
     *
     * The relationship uses the `region_section_widgets` pivot (RegionSectionWidget) and exposes pivot
     * fields `widget_id`, `position`, `config`, `flag`, and `metadata` on the `pivot` property.
     *
     * @return BelongsToMany Published Section models for this region, ordered by the pivot `position`.
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
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany A relation for the region's published widgets, ordered by the pivot `position`.
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
