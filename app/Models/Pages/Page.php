<?php

namespace App\Models\Pages;

use Atannex\Enables\HasSlug;
use Atannex\Enables\HasScope;
use Atannex\Relations\PageRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;
    use HasSlug;
    use HasScope;
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
