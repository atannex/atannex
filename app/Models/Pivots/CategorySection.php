<?php

namespace App\Models\Pivots;

use App\Models\Pages\Section;
use App\Models\Pages\Category;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class CategorySection
 *
 * Pivot model representing the many-to-many relationship between Categories and Sections.
 * Stores additional metadata such as configuration, position, and active status
 * for a Section within a Category. Supports soft deletes.
 *
 * @package App\Models\Pivots
 */
class CategorySection extends Pivot
{
    use SoftDeletes;

    /**
     * The table associated with the pivot model.
     *
     * @var string
     */
    protected $table = 'category_sections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id', // ID of the associated category
        'section_id',  // ID of the associated section
        'config',      // JSON configuration for this section within the category
        'position',    // Position of the section in the category
        'is_active',   // Whether the section is active in the category
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'config' => 'array',    // Cast JSON config to array
        'is_active' => 'boolean', // Ensure active flag is boolean
        'position' => 'integer',  // Ensure position is integer
    ];

    /**
     * Get the category that this pivot belongs to.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the section that this pivot belongs to.
     *
     * @return BelongsTo
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
}
