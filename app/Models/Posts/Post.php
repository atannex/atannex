<?php

namespace App\Models\Posts;

use App\Enums\Flag;
use App\Models\Tags\Tag;
use App\Contracts\Sluggable;
use App\Contracts\Commentable;
use Atannex\Traits\Bootable;
use Atannex\Enables\EnableSlug;
use Atannex\Enables\EnableScope;
use Atannex\Traits\Cleaning;
use Atannex\Relations\PostRelation;
use App\Livewire\Interactions\HasLikes;
use App\Livewire\Interactions\HasViews;
use Illuminate\Database\Eloquent\Model;
use App\Livewire\Interactions\HasShares;
use App\Livewire\Interactions\HasRatings;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Post
 *
 * Represents a post model with slug management, commenting, and interaction features.
 */
class Post extends Model implements Commentable, Sluggable
{
    use SoftDeletes;
    use EnableSlug;
    use EnableScope;
    use PostRelation;
    use Cleaning;
    use Bootable;
    use HasLikes;
    use HasRatings;
    use HasShares;
    use HasViews;

    /**
     * The source attribute for slug generation.
     */
    protected string $slugSource = 'title';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'slug_path',
        'date_path',
        'flag',
        'category_id',
        'author_id',
        'updated_by',
        'description',
        'image',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
        'flag' => Flag::class,
    ];

    /**
     * The model's default attribute values.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'flag' => Flag::DRAFT,
    ];

    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            if ($post->published_at) {
                $post->date_path = $post->published_at->format('m/Y');
            }
        });
    }

    /**
     * Get the base string for slug generation.
     *
     * @return string|null The category's slug path or null if no category exists.
     */
    public function getSlugBase(): ?string
    {
        return $this->category?->slug_path;
    }

    /**
     * Get the generated slug for the model.
     *
     * @return string|null The post's slug.
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Update slug paths for related tags.
     */
    public function cascadeSlugPathUpdates(): void
    {
        if ($categorySlug = $this->category?->slug_path) {
            $this->tags()->get()->each(function (Tag $tag) use ($categorySlug) {
                $tag->pivot?->forceFill([
                    'slug_path' => $this->buildSlugPath($categorySlug, $tag->slug),
                ])->saveQuietly();
            });
        }
    }

    /**
     * Clear slug paths for related tags.
     */
    public function clearRelatedSlugPaths(): void
    {
        $this->tags()->newPivotQuery()->update(['slug_path' => null]);
    }
}
