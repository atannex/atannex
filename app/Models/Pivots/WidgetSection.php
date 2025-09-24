<?php

namespace App\Models\Pivots;

use App\Models\Regions\Widget;
use App\Models\Regions\Section;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class WidgetSection
 *
 * Pivot model representing the relationship between Widgets and Sections.
 * Stores additional metadata such as configuration, position, and flag status.
 * Supports soft deletes for safe removal.
 *
 * @package App\Models\Pivots
 */
class WidgetSection extends Pivot
{
    use SoftDeletes;

    /**
     * The table associated with the pivot model.
     *
     * @var string
     */
    protected $table = 'widget_section';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'section_id',
        'widget_id',
        'config',
        'position',
        'flag',
        'metadata',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'config' => 'array',
        'metadata' => 'array',
        'position' => 'integer',
    ];

    /**
     * Get the section that this pivot belongs to.
     *
     * @return BelongsTo
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the widget that this pivot belongs to.
     *
     * @return BelongsTo
     */
    public function widget(): BelongsTo
    {
        return $this->belongsTo(Widget::class);
    }
}
