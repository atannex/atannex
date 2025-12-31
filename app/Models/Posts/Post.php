<?php

declare(strict_types=1);

namespace App\Models\Posts;

use App\Contracts\Commentable;
use Atannex\Concerns\HasBreaking;
use Atannex\Enables\Scoping;
use Atannex\Enables\Slugging;
use Atannex\Relations\PostRelation;
use Atannex\Traits\HasCleaning;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model implements Commentable
{
    use HasBreaking;
    use HasCleaning;
    use PostRelation;
    use Scoping;
    use Slugging;
    use SoftDeletes;

    protected string $slugSource = 'title';

    protected $fillable = [
        'title',
        'slug',
        'flag',
        'category_id',
        'author_id',
        'updated_by',
        'description',
        'image',
        'published_at',
        'slug_path',
    ];

    protected $casts = [
        'published_at'    => 'datetime',
    ];

    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    /* -----------------------------------------------------------------
     |  Image Handling (Universal)
     | -----------------------------------------------------------------
     */

    /**
     * Returns the image attributes for the model.
     * Can add more image fields here if needed.
     */
    public function images(): array
    {
        return ['image'];
    }

    /**
     * Returns the directory where images should be stored.
     */
    public function dir(): string
    {
        return 'posts';
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
