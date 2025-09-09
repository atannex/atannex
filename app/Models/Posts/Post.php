<?php

namespace App\Models\Posts;

use App\Enums\Flag;
use App\Contracts\Sluggable;
use App\Contracts\Commentable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Atannex\Traits\HasBootable;
use Atannex\Traits\HasCleaning;
use Atannex\Enables\EnableSlug;
use Atannex\Enables\EnableScope;
use Atannex\Relations\PostRelation;
use App\Livewire\Interactions\HasLikes;
use App\Livewire\Interactions\HasViews;
use App\Livewire\Interactions\HasShares;
use App\Livewire\Interactions\HasRatings;
use Atannex\Traits\HasBreaking;

/**
 * Class Post
 *
 * Represents a post with hierarchical slug management,
 * commenting, and interaction features.
 */
class Post extends Model implements Commentable, Sluggable
{
    use SoftDeletes;
    use EnableSlug;
    use EnableScope;
    use PostRelation;
    use HasCleaning;
    use HasBootable;
    use HasLikes;
    use HasRatings;
    use HasShares;
    use HasViews;
    use HasBreaking;

    /**
     * Source attribute for slug generation.
     */
    protected string $slugSource = 'title';

    /**
     * Mass assignable attributes.
     *
     * @var string[]
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
        'is_breaking',
        'breaking_until',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
        'breaking_until' => 'datetime',
        'is_breaking' => 'boolean',
        'flag' => Flag::class,
        'author_id' => 'integer',
        'category_id' => 'integer',
    ];

    /**
     * Model default attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'flag' => Flag::DRAFT,
    ];

    /**
     * Appended attributes for serialization.
     *
     * @var string[]
     */
    protected $appends = ['is_currently_breaking'];

    /**
     * Attributes that store image paths for cleanup.
     *
     * @return string[]
     */
    protected function imageAttributes(): array
    {
        return ['image'];
    }

    /**
     * Storage disk for image cleanup.
     *
     * @return string
     */
    protected function imageDisk(): string
    {
        return 'public';
    }

    /**
     * Automatically generate slug when title changes.
     *
     * @param string $value
     */
    public function setTitleAttribute(string $value): void
    {
        $this->attributes['title'] = $value;
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = $this->generateSlug($value);
        }
    }
}
