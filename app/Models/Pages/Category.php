<?php

namespace App\Models\Pages;

use Morfaw\Supports\Resolver;
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
        'slug_path',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($category) {
            $category->slug_path = $category->parent
                ? $category->parent->slug_path . '/' . $category->slug
                : $category->slug;
        });
    }
}
