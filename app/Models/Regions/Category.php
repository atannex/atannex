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
use Atannex\Enables\Scoping;
use Atannex\Enables\Slugging;
use Atannex\Filters\Hierarchy;
use Atannex\Traits\HasSlugPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model implements Sluggable
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

    /**
     * List the model's image attribute keys.
     *
     * @return string[] An array of attribute keys that store image filenames or paths (e.g. `['image']`).
     */
    public function images(): array
    {
        return ['image'];
    }

    /**
     * Storage directory name for this model's images.
     *
     * @return string Directory name used to store category images.
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
     * Get posts that belong to this category.
     *
     * @return HasMany The related Post models.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    /**
     * Get sections associated with this category via the `category_section` pivot table.
     *
     * The relation uses the custom pivot model `CategorySection`, includes the `flag` pivot
     * attribute, and maintains `created_at`/`updated_at` on the pivot.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany BelongsToMany relation to Section.
     */
    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'category_section')
            ->using(CategorySection::class)
            ->withPivot('flag')
            ->withTimestamps();
    }
}