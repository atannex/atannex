<?php

namespace App\Models\Regions;

use App\Models\Pivots\RegionSectionWidget;
use Atannex\Enables\Scoping;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Section extends Model
{
    use Scoping;
    use HasSlug;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'flag',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function getDomIdAttribute(): string
    {
        return 'section-' . $this->pivot->id;
    }

    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'region_section_widgets')
            ->using(RegionSectionWidget::class)
            ->withPivot(['widget_id', 'position', 'config', 'flag', 'metadata']);
    }

    public function widgets(): BelongsToMany
    {
        return $this->belongsToMany(Widget::class, 'region_section_widgets')
            ->using(RegionSectionWidget::class)
            ->withPivot(['region_id', 'position', 'config', 'flag', 'metadata']);
    }
}
