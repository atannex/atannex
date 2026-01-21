<?php

namespace App\Models\Pivots;

use App\Models\Posts\Post;
use App\Models\Tags\Tag;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * PostTag Pivot Model
 *
 * Represents the many-to-many relationship
 * between Post and Tag models.
 */
class PostTag extends Pivot
{
    /**
     * The table associated with the pivot model.
     */
    protected $table = 'post_tag';

    /**
     * Mass-assignable attributes.
     */
    protected $fillable = [
        'post_id',
        'tag_id',
    ];

    /**
     * Attribute casting for type safety.
     */
    protected $casts = [
        'post_id' => 'int',
        'tag_id'  => 'int',
    ];

    /**
     * Indicates that the pivot table includes timestamps.
     */
    public $timestamps = true;

    /**
     * Get the post associated with this pivot.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo The related Post model.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
         * Get the tag that owns this pivot record.
         *
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo The tag relationship instance.
         */
    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }
}
