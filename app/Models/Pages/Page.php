<?php

namespace App\Models\Pages;

use Atannex\Enables\EnableSlug;
use Atannex\Enables\EnableScope;
use Atannex\Relations\PageRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;
    use EnableSlug;
    use EnableScope;
    use PageRelation;

    protected string $slugSource = 'title';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'slug',
        'title',
        'parent_id',
        'metadata',
        'published_at',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'metadata' => 'array',
        'published_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
