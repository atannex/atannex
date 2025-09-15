<?php

namespace App\Models\Posts;

use App\Enums\Flag;
use App\Contracts\Sluggable;
use Atannex\Enables\HasSlug;
use Atannex\Enables\HasScope;
use App\Contracts\Commentable;
use Atannex\Traits\HasBootable;
use Atannex\Traits\HasBreaking;
use Atannex\Traits\HasCleaning;
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
 * Represents a post with hierarchical slug management,
 * commenting, and interaction features.
 */
class Post extends Model implements Commentable, Sluggable
{
    use SoftDeletes;
    use HasSlug;
    use HasScope;
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
        'breaking_until' => 'datetime:Y-m-d H:i:sP',
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
     * Image attribute used by HasCleaning trait.
     */
    public function getImageAttributeName(): string
    {
        return 'image';
    }

    /**
     * Directory used by HasCleaning trait.
     */
    public function getImageDirectory(): string
    {
        return 'posts';
    }

    /**
     * Automatically generate slug when title changes.
     *
     * @param string $value
     */
    protected function setTitleAttribute(string $value): void
    {
        $this->attributes['title'] = $value;
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = $this->generateSlug();
        }
    }
}
