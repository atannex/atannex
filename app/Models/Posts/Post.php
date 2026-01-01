<?php

declare(strict_types=1);

namespace App\Models\Posts;

use App\Contracts\Commentable;
use Atannex\Enables\Scoping;
use Atannex\Enables\Slugging;
use Atannex\Relations\PostRelation;
use Atannex\Traits\HasCleaning;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model implements Commentable
{
    use HasCleaning;
    use PostRelation;
    use Scoping;
    use Slugging;
    use SoftDeletes;

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
     * Lists the model attributes that store single-file images.
     *
     * @return string[] Array of attribute names used for image files (e.g., ['image']).
     */

    public function images(): array
    {
        return ['image'];
    }

    /**
     * Get the storage directory name used for post-related files.
     *
     * @return string The directory name used for storing post assets (e.g., "posts").
     */
    public function dir(): string
    {
        return 'posts';
    }

    /**
     * Indicates which model attribute should be used for route model binding.
     *
     * @return string The database column name used as the route key, 'slug'.
     */

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}