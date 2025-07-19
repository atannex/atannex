<?php

namespace Ngangagah\Relations;

use App\Models\Pages\Section;
use App\Models\Pages\WidgetRevision;
use App\Models\Pivots\WidgetSection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Trait Widget
 *
 * Provides relationship definitions for widget-related models, including
 * revisions and section associations.
 *
 * @package Ngangagah\Relations
 */
trait Widget
{

    /**
     * Get the sections that this widget belongs to in a many-to-many relationship.
     *
     * @return BelongsToMany<Section>
     */
    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'widget_sections')
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
