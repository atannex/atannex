<?php

namespace App\Models\Regions;

use App\Contracts\Sluggable;
use App\Enums\Flag;
use Atannex\Enables\HasSlug;
use Atannex\Traits\HasCleaning;
use Atannex\Traits\HasResolver;
use Atannex\Filters\GetHierarchy;
use Atannex\Builders\CategoryBuilder;
use Atannex\Relations\CategoryRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Category
 *
 * Represents a category model with slug management and hierarchical relationships.
 */
class Category extends Model implements Sluggable
{
    use SoftDeletes;
    use HasSlug;
    use CategoryRelation;
    use GetHierarchy;
    use HasResolver;
    use HasCleaning;
    use CategoryBuilder;

    /**
     * Image attribute used by HasCleaning trait.
     */
    public function getImageAttributeName(): string
    {
        return 'image';
    }

    /**
     * Directory used by HasCleaning trait.
     */
    public function getImageDirectory(): string
    {
        return 'category';
    }

    /**
     * Mass assignable attributes.
     *
     * @var array<string>
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
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'flag' => Flag::class
    ];

    /**
     * Source attribute for slug generation.
     *
     * @var string
     */
    protected string $slugSource = 'name';
}
