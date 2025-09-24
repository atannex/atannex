<?php

namespace App\Models\Regions;

use Atannex\Enables\HasSlug;
use App\Models\Pivots\WidgetSection;
use Atannex\Relations\SectionRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Widget
 *
 * Represents a Widget entity that can be associated with Sections.
 * Supports soft deletes, slugs, and stores metadata and status flags.
 *
 * @package App\Models\Pages
 */
class Widget extends Model
{
    use HasSlug;
    use SoftDeletes;

    /**
     * Source field for generating slug.
     *
     * @var string
     */
    protected string $slugSource = 'name';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'name',
        'flag',
        'metadata'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the sections that this widget belongs to in a many-to-many relationship.
     *
     * @return BelongsToMany<SectionRelation>
     */
    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'widget_section')
            ->using(WidgetSection::class)
            ->withPivot([
                'config',
                'position',
                'flag',
                'metadata',
                'created_at',
                'updated_at',
                'deleted_at',
            ])
            ->withTimestamps()
            ->wherePivot('deleted_at')
            ->orderByPivot('position');
    }
}
