<?php

declare(strict_types=1);

namespace App\Models\Regions;

use App\Contracts\Sluggable;
use App\Enums\Flag;
use Atannex\Concerns\HasResolver;
use Atannex\Enables\Scoping;
use Atannex\Enables\Slugging;
use Atannex\Filters\GetHierarchy;
use Atannex\Relations\CategoryRelation;
use Atannex\Traits\HasCleaning;
use Atannex\Traits\HasSlugPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model implements Sluggable
{
    use CategoryRelation;
    use GetHierarchy;
    use HasCleaning;
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

    /* -----------------------------------------------------------------
     |  Image Handling (Universal)
     | -----------------------------------------------------------------
     */

    /**
     * Return the image attributes for this model.
     * Add more fields if needed in the future.
     */
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
}
