<?php

namespace App\Models\Posts;

use App\Contracts\Commentable;
use App\Models\Regions\Employee;
use Atannex\Enables\Scoping;
use Atannex\Enables\Slugging;
use Atannex\Interactions\HasLikes;
use Atannex\Interactions\HasRatings;
use Atannex\Interactions\HasShares;
use Atannex\Interactions\HasViews;
use Atannex\Relations\PostRelation;
use Atannex\Traits\HasBreaking;
use Atannex\Traits\HasCleaning;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * Class Post
 *
 * Represents a post with hierarchical slug management,
 * commenting, and interaction features.
 */
class Post extends Model implements Commentable
{
    use HasBreaking;
    use HasCleaning;
    use HasLikes;
    use HasRatings;
    use HasShares;
    use HasViews;
    use PostRelation;
    use Scoping;
    use Slugging;
    use SoftDeletes;

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
        'published_at' => 'datetime',
        'breaking_until' => 'datetime',
        'feature_until' => 'datetime',
        'metadata' => 'array',
        'is_breaking' => 'boolean',
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
     * Check if the user is authenticated.
     */
    protected function isUserAuthenticated(): bool
    {
        return Auth::check();
    }
}
