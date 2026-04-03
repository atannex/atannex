<?php

namespace App\Models\Pivots;

use App\Models\Regions\Category;
use App\Models\Regions\Section;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategorySection extends Pivot
{
    use SoftDeletes;


    protected $table = 'category_section';

    protected $fillable = [
        'category_id',
        'section_id',
        'config',
        'flag',
        'metadata',
    ];

    protected $casts = [
        'config' => 'array',
        'metadata' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
}
