<?php

namespace Atannex\Relations;

use App\Models\Pivots\WidgetSection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait Widget
 *
 * Provides relationship definitions for widget-related models, including
 * revisions and section associations.
 *
 * @package Ngangagah\Relations
 */
trait WidgetRelation
{

    /**
     * Get the sections that this widget belongs to in a many-to-many relationship.
     *
     * @return BelongsToMany<SectionRelation>
     */
    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(SectionRelation::class, 'widget_sections')
            ->using(WidgetSection::class)
            ->withPivot([
                'config',
                'position',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at',
            ])
            ->withTimestamps()
            ->wherePivot('deleted_at', null)
            ->orderByPivot('position');
    }
}
