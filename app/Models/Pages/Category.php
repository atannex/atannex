<?php

namespace App\Models\Pages;

use Morfaw\Supports\Resolver;
use Morfaw\Supports\EnablePath;
use Morfaw\Supports\EnableSlug;
use Morfaw\Supports\EnableScope;
use Atangageih\Filters\Hierarchy;
use Illuminate\Database\Eloquent\Model;
use Ngangagah\Relations\CategoryRelation;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    use EnableSlug;
    use EnablePath;
    use CategoryRelation;
    use EnableScope;
    use Hierarchy;
    use Resolver;

    protected string $slugSource = 'name';

    protected $fillable = [
        'name',
        'slug',
        'flag',
        'image',
        'description',
        'published_at',
        'parent_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
