<?php

namespace App\Models\Pivots;

use App\Models\Pages\Page;
use App\Models\Pages\Section;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageSection extends Pivot
{
    use SoftDeletes;

    protected $table = 'page_section';

    protected $fillable = [
        'page_id',
        'section_id',
        'config',
        'position',
        'is_active',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
}
