<?php

namespace App\Models\Pages;

use App\Contracts\Sluggable;
use Atannex\Builders\CategoryBuilder;
use Atannex\Traits\Resolver;
<<<<<<< HEAD
use Atannex\Enables\EnableSlug;
use Atannex\Enables\EnableScope;
=======
use Atannex\Enables\Slug;
use Atannex\Enables\Scope;
>>>>>>> b90bee7 (SEO for news → news_keywords, article:section, published_time improve indexing by Google News.)
use Atannex\Filters\GetHierarchy;
use Atannex\Relations\CategoryRelation;
use Atannex\Traits\Cleaning;
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
<<<<<<< HEAD
    use EnableSlug;
    use CategoryRelation;
    use EnableScope;
=======
    use Slug;
    use CategoryRelation;
    use Scope;
>>>>>>> b90bee7 (SEO for news → news_keywords, article:section, published_time improve indexing by Google News.)
    use GetHierarchy;
    use Resolver;
    use Cleaning;
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
