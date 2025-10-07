<?php

namespace App\Models\Pivots;

use App\Enums\Flag;
use App\Models\Regions\Widget;
use App\Models\Regions\Section;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class WidgetSection
 *
 * Pivot model representing the relationship between Widgets and Sections.
 * Handles configuration, position, metadata, and publishing status.
 * Supports soft deletes for safe removal.
 */
class WidgetSection extends Pivot
{
    use SoftDeletes;

    protected $table = 'widget_section';

    protected $fillable = [
        'section_id',
        'widget_id',
        'config',
        'position',
        'flag',
        'metadata',
    ];

    protected $casts = [
        'config'   => 'array',
        'metadata' => 'array',
        'position' => 'integer',
    ];

    /**
     * Section relation.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Widget relation.
     */
    public function widget(): BelongsTo
    {
        return $this->belongsTo(Widget::class);
    }
}
