<?php

declare(strict_types=1);

namespace App\Models\Regions;

use App\Enums\Flag;
use App\Contracts\Sluggable;
use Atannex\Enables\Slugging;
use Atannex\Traits\HasCleaning;
use Atannex\Traits\HasResolver;
use Atannex\Traits\HasSlugPath;
use Atannex\Filters\GetHierarchy;
use Atannex\Relations\CategoryRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model implements Sluggable
{
    use CategoryRelation;
    use GetHierarchy;
    use HasCleaning;
    use HasResolver;
    use HasSlugPath;
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

    public function getImageAttributeName(): string
    {
        return 'image';
    }

    public function getImageDirectory(): string
    {
        return 'category';
    }
}
