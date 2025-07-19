<?php

namespace App\Models\Pivots;

use App\Models\Pages\Widget;
use App\Models\Pages\Section;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WidgetSection extends Pivot
{
    use SoftDeletes;

    protected $table = 'widget_sections';

    protected $fillable = [
        'section_id',
        'widget_id',
        'config',
        'position',
        'is_active',
    ];

    protected $casts = [
        'config' => 'array',
        'position' => 'integer',
        'is_active' => 'boolean',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function widget(): BelongsTo
    {
        return $this->belongsTo(Widget::class, 'widget_id');
    }
}
