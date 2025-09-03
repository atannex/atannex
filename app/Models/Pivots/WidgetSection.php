<?php

namespace App\Models\Pivots;

use App\Models\Pages\Widget;
use App\Models\Pages\Section;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class WidgetSection
 *
 * Pivot model representing the relationship between Widgets and Sections.
 * Utilizes soft deletes and provides metadata about the widget's position
 * and configuration within a section.
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
    protected $table = 'widget_sections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'section_id', // ID of the associated section
        'widget_id',  // ID of the associated widget
        'config',     // JSON configuration for the widget in this section
        'position',   // Position of the widget within the section
        'is_active',  // Whether the widget is active in this section
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'config' => 'array',   // Automatically cast the JSON config to an array
        'position' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the section that this pivot belongs to.
     *
     * @return BelongsTo
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    /**
     * Get the widget that this pivot belongs to.
     *
     * @return BelongsTo
     */
    public function widget(): BelongsTo
    {
        return $this->belongsTo(Widget::class, 'widget_id');
    }
}
