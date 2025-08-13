<?php

namespace App\Models\Tags;

use App\Models\Posts\Post;
use App\Models\Pivots\PostTag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morfaw\Supports\EnableSlug;

class Tag extends Model
{
    use SoftDeletes, EnableSlug;

    /**
     * Source field for slug generation.
     *
     * @var string
     */
    protected string $slugSource = 'name';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
    ];

    /**
     * Get the parent tag.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Tag::class, 'parent_id');
    }

    /**
     * Get the child tags.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Tag::class, 'parent_id');
    }

    /**
     * The posts that belong to the tag.
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class)
            ->using(PostTag::class)
            ->withTimestamps()
            ->withPivot('id', 'post_id', 'tag_id', 'slug_path');
    }
}
