<?php

namespace App\Models\Tags;

use App\Models\Posts\Post;
use App\Models\Pivots\PostTag;
use Atannex\Builders\TagBuilder;
<<<<<<< HEAD
use Atannex\Enables\EnableSlug;
=======
use Atannex\Enables\Slug;
>>>>>>> b90bee7 (SEO for news → news_keywords, article:section, published_time improve indexing by Google News.)
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use SoftDeletes;
<<<<<<< HEAD
    use EnableSlug;
=======
    use Slug;
>>>>>>> b90bee7 (SEO for news → news_keywords, article:section, published_time improve indexing by Google News.)
    use TagBuilder;

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
        'parent_id',
    ];

    /**
     * Relationships to always eager load.
     *
     * @var array<string>
     */
    protected $with = [
        'children',
    ];


    /**
     * Get the parent tag.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Tag::class, 'parent_id');
    }

    /**
     * Get the immediate child tags.
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
            ->withPivot('slug_path');
    }
}
