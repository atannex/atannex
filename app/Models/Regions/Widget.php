<?php

namespace App\Models\Regions;

use App\Models\Pivots\RegionSectionWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Widget extends Model
{
    use HasSlug;
    use SoftDeletes;

    protected $fillable = [
        'slug',
        'name',
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

    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'region_section_widgets')
            ->using(RegionSectionWidget::class)
            ->withPivot(['section_id', 'position', 'config', 'flag', 'metadata']);
    }

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'region_section_widgets')
            ->using(RegionSectionWidget::class)
            ->withPivot(['region_id', 'position', 'config', 'flag', 'metadata']);
    }
}
