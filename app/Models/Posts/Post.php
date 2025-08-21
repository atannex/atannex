<?php

namespace App\Models\Posts;

use App\Enums\Flag;
use App\Models\Tags\Tag;
use App\Contracts\Sluggable;
use App\Contracts\Commentable;
use App\Models\Pivots\PostTag;
use App\Models\Traits\Bootable;
use Morfaw\Supports\EnableSlug;
use Morfaw\Supports\EnableScope;
use Ngangagah\Relations\PostRelation;
use Morfaw\Orchestrators\ImageCleanup;
use App\Livewire\Interactions\HasLikes;
use App\Livewire\Interactions\HasViews;
use Illuminate\Database\Eloquent\Model;
use App\Livewire\Interactions\HasShares;
use App\Livewire\Interactions\HasRatings;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model implements Commentable, Sluggable
{
    use SoftDeletes;
    use EnableSlug;
    use EnableScope;
    use PostRelation;
    use ImageCleanup;
    use Bootable;
    use HasLikes;
    use HasRatings;
    use HasShares;
    use HasViews;

    /**
     * The source attribute for slug generation.
     *
     * @var string
     */
    protected string $slugSource = 'title';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'title',
        'slug',
        'slug_path',
        'flag',
        'category_id',
        'author_id',
        'updated_by',
        'description',
        'image',
        'published_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * The model's default attribute values.
     *
     * @var array<string,mixed>
     */
    protected $attributes = [
        'flag' => Flag::DRAFT,
    ];

    public function getSlugBase(): string
    {
        return $this->category->slug_path;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function cascadeSlugPathUpdates(): void
    {
        $categorySlug = $this->category?->slug_path;

        if ($categorySlug) {
            $this->tags()->get()->each(function (Tag $tag) use ($categorySlug) {
                $pivot = $tag->pivot;
                if ($pivot) {
                    $pivot->slug_path = $this->buildSlugPath($categorySlug, $tag->slug);
                    $pivot->saveQuietly();
                }
            });
        }
    }

    public function clearRelatedSlugPaths(): void
    {
        $this->tags()->update(['slug_path' => null]);
    }
}
