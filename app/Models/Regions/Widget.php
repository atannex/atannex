<?php

namespace App\Models\Regions;

use App\Models\Pivots\RegionSectionWidget;
use Atannex\Enables\Slugging;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Widget
 *
 * Represents a Widget entity that can be associated with Sections.
 * Supports soft deletes, slugs, and stores metadata and status flags.
 */
class Widget extends Model
{
    use Slugging;
    use SoftDeletes;

    /**
     * Source field for generating slug.
     */
    protected string $slugSource = 'name';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'name',
        'flag',
        'metadata',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
    ];

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'region_section_widgets')
            ->using(RegionSectionWidget::class)
            ->withPivot(['region_id', 'config', 'flag', 'metadata']);
    }
}
