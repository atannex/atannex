<?php

declare(strict_types=1);

namespace App\Models\Regions;

use App\Contracts\Sluggable;
use App\Enums\Flag;
use Atannex\Enables\Slugging;
use Atannex\Filters\GetHierarchy;
use Atannex\Relations\CategoryRelation;
use Atannex\Traits\HasCleaning;
use Atannex\Traits\HasResolver;
use Atannex\Traits\HasSlugPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Category
 *
 * Represents a hierarchical category with slug path generation,
 * soft deletes, media cleanup, and advanced relationship resolution.
 */
class Category extends Model implements Sluggable
{
    // Handles automatic slug generation
    use CategoryRelation;
    // Defines category-specific parent/child relations
    use GetHierarchy;           // Enables key-based lookup resolution
    use HasCleaning;  // Adds utilities for nested category hierarchies
    use HasResolver;      // Handles media cleanup for image attributes
    use HasSlugPath;
    use Slugging;
    use SoftDeletes;       // Automatically maintains hierarchical slug paths

    /**
     * The database table used by the model.
     */
    protected $table = 'categories';

    /**
     * Attributes that can be mass-assigned.
     */
    protected $fillable = [
        'name',
        'slug',
        'flag',
        'image',
        'description',
        'parent_id',
        'slug_path',
    ];

    /**
     * Type casting for model attributes.
     */
    protected $casts = [
        'flag' => Flag::class,
    ];

    /**
     * Source attribute used to generate the slug (via HasSlug).
     */
    protected string $slugSource = 'name';

    /**
     * Get the name of the image attribute for cleanup handling.
     */
    public function getImageAttributeName(): string
    {
        return 'image';
    }

    /**
     * Get the directory name where the model's image is stored.
     */
    public function getImageDirectory(): string
    {
        return 'category';
    }

    /**
     * Model boot logic.
     *
     * Ensures HasSlugPath trait's observers are registered.
     */
    protected static function booted(): void
    {
        static::bootHasSlugPath();
    }
}
