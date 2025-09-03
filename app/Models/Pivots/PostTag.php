<?php

namespace App\Models\Pivots;

use App\Contracts\Sluggable;
use App\Models\Posts\Post;
use App\Models\Tags\Tag;
use Atannex\Builders\PostTagBuilder;
use Atannex\Traits\Bootable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class PostTag
 *
 * Pivot model representing the post-tag relationship with slug management.
 */
class PostTag extends Pivot implements Sluggable
{
    use Bootable;
    use PostTagBuilder;

    /**
     * The table associated with the pivot model.
     *
     * @var string
     */
    protected $table = 'post_tag';

    /**
     * Mass assignable attributes.
     *
     * @var array<string>
     */
    protected $fillable = [
        'post_id',
        'tag_id',
        'slug_path',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'post_id' => 'integer',
        'tag_id' => 'integer',
    ];

    /**
     * Get the post associated with this pivot.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the tag associated with this pivot.
     */
    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }
}
