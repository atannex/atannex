<?php

namespace App\Models\Pivots;

use App\Enums\Flag;
use App\Models\Posts\Post;
use App\Models\Tags\Tag;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class PostTag
 *
 * Pivot model representing the post-tag relationship with slug and publishing management.
 */
class PostTag extends Pivot
{
    use SoftDeletes;

    protected $table = 'post_tag';

    protected $fillable = [
        'post_id',
        'tag_id',
        'slug_path',
        'flag',
    ];

    protected $casts = [
        'post_id'   => 'integer',
        'tag_id'    => 'integer',
        'slug_path' => 'string',
    ];

    /**
     * Boot model with default global scopes.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('published', function (Builder $builder) {
            $builder->where('flag', Flag::PUBLISHED);
        });
    }

    /**
     * Scope for only published pivots.
     */
    protected function scopePublished(Builder $query): Builder
    {
        return $query->where('flag', Flag::PUBLISHED);
    }

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
