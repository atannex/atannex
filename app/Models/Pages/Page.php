<?php

namespace App\Models\Pages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morfaw\Supports\EnableScope;
use Morfaw\Supports\EnableSlug;
use Ngangagah\Relations\PageRelation;

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
