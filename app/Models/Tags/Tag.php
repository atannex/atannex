<?php

namespace App\Models\Tags;

use App\Models\Posts\Post;
use Atannex\Enables\HasSlug;
use App\Models\Pivots\PostTag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use SoftDeletes;
    use HasSlug;

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
