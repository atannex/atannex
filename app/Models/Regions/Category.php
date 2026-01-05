<?php

declare(strict_types=1);

namespace App\Models\Regions;

use App\Contracts\Sluggable;
use App\Models\Pivots\CategorySection;
use App\Models\Posts\Post;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\Flag;
use Atannex\Concerns\HasResolver;
use Atannex\Contracts\HasImages;
use Atannex\Enables\Scoping;
use Atannex\Enables\Slugging;
use Atannex\Filters\Hierarchy;
use Atannex\Traits\HasSlugPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model implements Sluggable, HasImages
{
    use Hierarchy;
    use HasResolver;
    use Slugging;
    use HasSlugPath;
    use Scoping;
    use SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'slug',
        'flag',
        'image',
        'description',
        'parent_id',
        'slug_path',
    ];

    protected $casts = [
        'flag' => Flag::class,
    ];

    protected string $slugSource = 'name';

    public function images(): array
    {
        return ['image'];
    }

    /**
     * Return the storage directory for images.
     */
    public function dir(): string
    {
        return 'category';
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

    /**
     * Posts under this category.
     * Standard one-to-many relationship.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    /**
     * Sections associated with this category via the pivot table `category_section`.
     * Includes pivot fields `config` and `flag`.
     * Uses timestamps on the pivot table and a custom pivot model `CategorySection`.
     */
    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'category_section')
            ->using(CategorySection::class)
            ->withPivot('flag')
            ->withTimestamps();
    }
}
