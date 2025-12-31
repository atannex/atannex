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
use Atannex\Traits\HasCleaning;
use Atannex\Traits\HasSlugPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Region
 *
 * Represents a hierarchical region with support for slug paths,
 * scoped queries, soft deletion, logo image handling, and custom metadata.
 */
class Region extends Model implements Sluggable
{
    use GetHierarchy;
    use HasSlugPath;
    use RegionRelation;
    use Scoping;
    use Slugging;
    use HasCleaning; // Added for automatic logo image cleanup
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
        'flag'      => Flag::class,
        'metadata'  => 'array',
        'territory' => Territories::class,
    ];

    /* -----------------------------------------------------------------
     |  Image Handling
     | -----------------------------------------------------------------
     */

    /**
     * Return the image attributes for this model.
     */
    public function images(): array
    {
        return ['logo'];
    }

    /**
     * Return the directory where logos should be stored.
     */
    public function dir(): string
    {
        return 'regions/logos';
    }

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
