<?php

namespace App\Models\Tags;

use App\Models\Pivots\PostTag;
use App\Models\Posts\Post;
use Atannex\Enables\Slugging;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use Slugging;
    use SoftDeletes;

    /**
     * Source field for slug generation.
     */
    protected string $slugSource = 'name';

    /**
     * Attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'slug',
    ];

    /**
     * The posts that belong to the tag.
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class)
            ->using(PostTag::class)
            ->withTimestamps();
    }
}
