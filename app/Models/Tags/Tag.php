<?php

namespace App\Models\Tags;

use App\Models\Posts\Post;
use App\Models\Pivots\PostTag;
use Atannex\Enables\EnableSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use SoftDeletes;
    use EnableSlug;

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
     * Boot method to handle cascading soft deletes.
     */
    protected static function booted(): void
    {
        static::deleting(function (Tag $tag) {
            $tag->children()->delete();
        });
    }

    /**
     * Get the parent tag.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Tag::class, 'parent_id')->withDefault();
    }

    /**
     * Get the immediate child tags.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Tag::class, 'parent_id');
    }

    /**
     * Get all descendant tags recursively.
     */
    public function allChildren(): HasMany
    {
        return $this->children()->with('allChildren');
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

    /**
     * Scope to fetch only top-level tags.
     */
    protected function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }
}
