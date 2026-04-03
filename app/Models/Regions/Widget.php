<?php

namespace App\Models\Regions;

use App\Models\Pivots\RegionSectionWidget;
use Atannex\Foundation\Concerns\GeneratesSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Widget extends Model
{
    use GeneratesSlug;
    use SoftDeletes;

    protected string $slugMode      = self::MODE_RANDOM;

    protected string $slugColumn    = 'name';

    protected string|array $slugSource = 'title';

    protected string $slugSeparator = '-';

    protected ?int $slugMaxLength   = 100;

    protected $fillable = [
        'slug',
        'name',
        'flag',
        'metadata',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get regions that use this widget.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany Relationship to Region using the RegionSectionWidget pivot and including pivot columns `section_id`, `position`, `config`, `flag`, `metadata`.
     */
    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'region_section_widgets')
            ->using(RegionSectionWidget::class)
            ->withPivot(['section_id', 'position', 'config', 'flag', 'metadata']);
    }

    /**
     * Get sections this widget is placed in across regions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany BelongsToMany relation to Section including pivot attributes `region_id`, `position`, `config`, `flag`, and `metadata`.
     */
    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'region_section_widgets')
            ->using(RegionSectionWidget::class)
            ->withPivot(['region_id', 'position', 'config', 'flag', 'metadata']);
    }
}
