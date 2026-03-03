<?php

declare(strict_types=1);

namespace App\Models\Regions;

use App\Contracts\Sluggable;
use App\Enums\Flag;
use App\Models\Pivots\CategorySection;
use App\Models\Posts\Post;
use App\Models\Posts\Video;
use Atannex\Enables\Scoping;
use Atannex\Enables\Slugging;
use Atannex\Filters\Hierarchy;
use Atannex\Traits\HasSlugPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model implements Sluggable
{
    use HasSlugPath;
    use Hierarchy;
    use Scoping;
    use Slugging;
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

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::bootHasSlugPath();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'category_section')
            ->using(CategorySection::class)
            ->withPivot('flag')
            ->withTimestamps();
    }

    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }
}
