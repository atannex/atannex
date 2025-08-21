<?php

namespace App\Models\Pages;

use Morfaw\Supports\Resolver;
use App\Models\Traits\Bootable;
use Morfaw\Supports\EnableSlug;
use Morfaw\Supports\EnableScope;
use Atangageih\Filters\GetHierarchy;
use Illuminate\Database\Eloquent\Model;
use Ngangagah\Relations\CategoryRelation;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    use EnableSlug;
    use CategoryRelation;
    use EnableScope;
    use GetHierarchy;
    use Resolver;
    use Bootable;

    protected string $slugSource = 'name';

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

    protected $casts = [
        'published_at' => 'datetime',
    ];

}
