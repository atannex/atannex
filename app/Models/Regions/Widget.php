<?php

namespace App\Models\Regions;

use Atannex\Enables\HasSlug;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pivots\RegionSectionWidget;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Widget
 *
 * Represents a Widget entity that can be associated with Sections.
 * Supports soft deletes, slugs, and stores metadata and status flags.
 *
 * @package App\Models\Pages
 */
class Widget extends Model
{
    use HasSlug;
    use SoftDeletes;

    /**
     * Source field for generating slug.
     *
     * @var string
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
        'metadata'
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
