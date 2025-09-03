<?php

namespace App\Models\Pivots;

use App\Models\Pages\Page;
use App\Models\Pages\Section;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PageSection
 *
 * Pivot model representing the relationship between Pages and Sections.
 * Allows storing additional metadata such as configuration, position,
 * and active status for a Section within a Page. Supports soft deletes.
 *
 * @package App\Models\Pivots
 */
class PageSection extends Pivot
{
    use SoftDeletes;

    /**
     * The table associated with the pivot model.
     *
     * @var string
     */
    protected $table = 'page_section';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'page_id',    // ID of the associated page
        'section_id', // ID of the associated section
        'config',     // JSON configuration specific to this section in the page
        'position',   // Position of the section within the page
        'is_active',  // Whether the section is active in the page
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'config' => 'array',    // Automatically cast JSON config to array
        'is_active' => 'boolean', // Ensure active flag is boolean
    ];

    /**
     * Get the page that this pivot belongs to.
     *
     * @return BelongsTo
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
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
