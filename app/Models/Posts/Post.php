<?php

namespace App\Models\Posts;

use App\Contracts\Commentable;
use App\Models\Regions\Employee;
use Atannex\Concerns\HasBreaking;
use Atannex\Enables\Scoping;
use Atannex\Enables\Slugging;
use Atannex\Interactions\HasLikes;
use Atannex\Interactions\HasRatings;
use Atannex\Interactions\HasShares;
use Atannex\Interactions\HasViews;
use Atannex\Relations\PostRelation;
use Atannex\Traits\HasCleaning;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

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
     * The field used as the source for slug generation.
     * Spatie Sluggable will generate or regenerate the slug using this column.
     */
    protected string $slugSource = 'title';

    /**
     * Mass assignable attributes for a Post.
     *
     * @var array
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
     * Attribute casting rules.
     *
     * @var array
     */
    protected $casts = [
        'published_at' => 'datetime',
        'breaking_until' => 'datetime',
        'feature_until' => 'datetime',
        'metadata' => 'array',
        'is_breaking' => 'boolean',
    ];

    /**
     * Date attributes handled by Eloquent.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attribute name for the post's primary image field.
     *
     * @return string
     */
    public function getImageAttributeName(): string
    {
        return 'image';
    }

    /**
     * The directory where post images should be stored.
     *
     * @return string
     */
    public function getImageDirectory(): string
    {
        return 'posts';
    }

    /**
     * User who last updated the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updatedBy()
    {
        return $this->belongsTo(Employee::class, 'updated_by');
    }

    /**
     * Determine whether the current user is authenticated.
     *
     * @return bool
     */
    protected function isUserAuthenticated(): bool
    {
        return Auth::check();
    }

    /**
     * Build the slug_path by combining the category's slug_path with the post's slug.
     * This ensures hierarchical URLs like: /news/local/my-post-title
     *
     * @return void
     */
    public function refreshSlugPath(): void
    {
        $category = $this->category()
            ->withoutGlobalScopes()
            ->select(['id', 'slug_path'])
            ->first();

        $this->slug_path = trim($category->slug_path . '/' . $this->slug, '/');
    }

    /**
     * Model boot method.
     *
     * Handles automatic regeneration of slug_path whenever:
     *   - The slug changes
     *   - The category changes
     *
     * Ensures slug_path is always kept in sync before and after save.
     */
    protected static function boot()
    {
        parent::boot();

        /**
         * Before saving the post, update slug_path if slug or category_id changed.
         */
        static::saving(function (Post $post) {
            if ($post->isDirty(['slug', 'category_id'])) {
                $post->refreshSlugPath();
                $post->slug_path = $post->slug_path;
            }
        });

        /**
         * After saving, ensure the slug_path column is updated silently.
         */
        static::saved(function (Post $post) {
            if ($post->wasChanged(['slug', 'category_id'])) {
                $post->refreshSlugPath();
                $post->updateQuietly(['slug_path' => $post->slug_path]);
            }
        });
    }
}
