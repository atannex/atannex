<?php

namespace App\Models\Regions;

use App\Contracts\Sluggable;
use Atannex\Builders\RegionBuilder;
use Atannex\Enables\EnableSlug;
use Atannex\Enables\EnableScope;
use Atannex\Filters\GetHierarchy;
use Atannex\Relations\RegionRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Region
 *
 * Represents a hierarchical region with dynamic slug management.
 */
class Region extends Model implements Sluggable
{
    use SoftDeletes;
    use RegionRelation;
    use EnableSlug;
    use EnableScope;
    use GetHierarchy;
    use RegionBuilder;

    /**
     * The source field used for slug generation.
     *
     * @var string
     */
    protected string $slugSource = 'name';

    /**
     * Mass assignable attributes.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'flag',
        'slug',
        'slug_path',
        'type',
        'logo',
        'description',
        'parent_id',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'flag' => 'string',
    ];
}
