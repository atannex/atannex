<?php

namespace App\Models\Pivots;

use App\Models\Regions\Section;
use App\Models\Regions\Category;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Pivot model representing the many-to-many relationship between Categories and Sections.
 *
 * Stores additional data for a Section within a Category, including:
 * - `config` (JSON configuration)
 * - `flag` (status indicator: active, pending, etc.)
 * - `metadata` (arbitrary metadata)
 *
 * Supports soft deletes to safely remove relations without losing historical data.
 */
class CategorySection extends Pivot
{
    use SoftDeletes;

    /**
     * The table associated with the pivot model.
     */
    protected $table = 'category_section';

    /**
     * Attributes that are mass assignable.
     * Only these attributes can be updated via mass assignment.
     */
    protected $fillable = [
        'category_id',
        'section_id',
        'config',
        'flag',
        'metadata',
    ];

    /**
     * Attributes that should be cast to native types.
     */
    protected $casts = [
        'config' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Define the relationship to the Category model.
     * Each pivot belongs to a single Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Define the relationship to the Section model.
     * Each pivot belongs to a single Section.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
}
