<?php

namespace App\Models\Posts;

use App\Contracts\Sluggable;
use Atannex\Enables\HasSlug;
use Atannex\Enables\HasScope;
use App\Contracts\Commentable;
use Atannex\Traits\HasBootable;
use Atannex\Traits\HasBreaking;
use Atannex\Traits\HasCleaning;
use App\Models\Regions\Employee;
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
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'flag',
        'category_id',
        'author_id',
        'updated_by',
        'description',
        'image',
        'published_at',
        'metadata',
        'slug_path',
        'is_breaking',
        'breaking_until',
        'feature_until',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at'   => 'datetime',
        'breaking_until' => 'datetime',
        'feature_until'  => 'datetime',
        'metadata'       => 'array',
        'is_breaking'    => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     * (SoftDeletes and timestamps)
     *
     * @var array<string>
     */
    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at',
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
     * Get the employee who last updated the post.
     */
    public function updatedBy()
    {
        return $this->belongsTo(Employee::class, 'updated_by');
    }

    /**
     * Scope a query to only include published posts.
     */
    protected function scopePublished($query)
    {
        return $query->where('flag', 'published')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope a query to include only breaking news.
     */
    protected function scopeBreaking($query)
    {
        return $query->where('is_breaking', true)
            ->where(function ($q) {
                $q->whereNull('breaking_until')
                    ->orWhere('breaking_until', '>=', now());
            });
    }
}
