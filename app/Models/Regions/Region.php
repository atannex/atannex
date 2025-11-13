<?php

declare(strict_types=1);

namespace App\Models\Regions;

use App\Contracts\Sluggable;
use App\Enums\Flag;
use App\Enums\Territories;
use Atannex\Enables\Scoping;
use Atannex\Enables\Slugging;
use Atannex\Filters\GetHierarchy;
use Atannex\Relations\RegionRelation;
use Atannex\Traits\HasSlugPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Region
 *
 * Represents a hierarchical region with support for slug paths,
 * scoped queries, soft deletion, and custom metadata.
 */
class Region extends Model implements Sluggable
{
    use GetHierarchy;
    use HasSlugPath;
    use RegionRelation;
    use Scoping;
    use Slugging;
    use SoftDeletes;

    /**
     * The table associated with this model.
     */
    protected $table = 'regions';

    /**
     * Attribute used as the source when generating slugs.
     */
    protected string $slugSource = 'name';

    /**
     * Attributes that can be mass-assigned.
     */
    protected $fillable = [
        'name',
        'flag',
        'slug',
        'territory',
        'logo',
        'description',
        'slug_path',
        'metadata',
        'parent_id',
    ];

    /**
     * Attribute casting rules for this model.
     */
    protected $casts = [
        'flag' => Flag::class,                  // Enum casting for feature flags
        'metadata' => 'array',                  // JSON column casting
        'territory' => Territories::class,      // Enum casting for region type
    ];

    /**
     * Register model event hooks.
     *
     * Ensures HasSlugPath trait is properly initialized after booting.
     */
    protected static function booted(): void
    {
        static::bootHasSlugPath();
    }
}
