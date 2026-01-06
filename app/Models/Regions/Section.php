<?php

namespace App\Models\Regions;

use App\Models\Pivots\RegionSectionWidget;
use Atannex\Enables\Scoping;
use Atannex\Enables\Slugging;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use Scoping;
    use Slugging;
    use SoftDeletes;

    protected string $slugSource = 'name';

    protected $fillable = [
        'name',
        'slug',
        'flag',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Produce a DOM id for this section when accessed via a pivot (for example within a region).
     *
     * @return string The DOM id in the format 'section-{pivot_id}', where {pivot_id} is the pivot record's id.
     */
    protected function getDomIdAttribute(): string
    {
        return 'section-' . $this->pivot->id;
    }

    /**
     * Get regions that include this section.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany BelongsToMany relation to Region including pivot columns widget_id, position, config, flag, and metadata.
     */
    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'region_section_widgets')
            ->using(RegionSectionWidget::class)
            ->withPivot(['widget_id', 'position', 'config', 'flag', 'metadata']);
    }

    /**
     * Get widgets placed in this section across all regions.
     *
     * @return BelongsToMany BelongsToMany relation to Widget with pivot columns `region_id`, `position`, `config`, `flag`, and `metadata`.
     */
    public function widgets(): BelongsToMany
    {
        return $this->belongsToMany(Widget::class, 'region_section_widgets')
            ->using(RegionSectionWidget::class)
            ->withPivot(['region_id', 'position', 'config', 'flag', 'metadata']);
    }
}