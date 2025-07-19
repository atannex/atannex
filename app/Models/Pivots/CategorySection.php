<?php

namespace App\Models\Pivots;

use App\Models\Pages\Section;
use App\Models\Pages\Category;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CategorySection extends Pivot
{
    use SoftDeletes;

    protected $table = 'category_sections';

    protected $fillable = [
        'category_id',
        'section_id',
        'config',
        'position',
        'is_active',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
        'position' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
}
