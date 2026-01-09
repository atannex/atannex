<?php

declare(strict_types=1);

namespace App\Models\Posts;

use Atannex\Enables\Scoping;
use Atannex\Enables\Slugging;
use App\Contracts\Commentable;
use App\Models\Traits\HandlePost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model implements Commentable
{
    use HandlePost;
    use Scoping;
    use Slugging;
    use SoftDeletes;

    /**
     * Slug source field.
     */
    protected string $slugSource = 'title';

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'author_id',
        'updated_by',
        'description',
        'image',
        'published_at',
        'slug_path',

        'is_breaking',
        'breaking_at',
        'breaking_expires',

        'is_editor_pick',
        'editor_pick_at',
        'editor_pick_expires',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'published_at'        => 'datetime',
        'is_breaking'         => 'boolean',
        'breaking_at'         => 'datetime',
        'breaking_expires'    => 'datetime',

        'is_editor_pick'      => 'boolean',
        'editor_pick_at'      => 'datetime',
        'editor_pick_expires' => 'datetime',
    ];

    /**
     * Use slug as route key.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
