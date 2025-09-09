<?php

namespace App\Models\Pages;

use App\Contracts\Sluggable;
use Atannex\Builders\CategoryBuilder;
use Atannex\Traits\HasResolver;
use Atannex\Enables\EnableSlug;
use Atannex\Enables\EnableScope;
use Atannex\Filters\GetHierarchy;
use Atannex\Relations\CategoryRelation;
use Atannex\Traits\HasCleaning;
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
    use EnableSlug;
    use CategoryRelation;
    use EnableScope;
    use GetHierarchy;
    use HasResolver;
    use HasCleaning;
    use CategoryBuilder;

    /**
     * Attributes that store image paths.
     *
     * @return array<string>
     */
    protected function imageAttributes(): array
    {
        return ['image'];
    }

    /**
     * Storage disk for image cleanup.
     *
     * @return string
     */
    protected function imageDisk(): string
    {
        return 'public';
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
        'published_at',
        'parent_id',
        'slug_path',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Source attribute for slug generation.
     *
     * @var string
     */
    protected string $slugSource = 'name';
}
