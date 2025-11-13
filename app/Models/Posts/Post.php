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

    protected string $slugSource = 'title';

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

    protected $casts = [
        'published_at'    => 'datetime',
        'breaking_until'  => 'datetime',
        'feature_until'   => 'datetime',
        'metadata'        => 'array',
        'is_breaking'     => 'boolean',
    ];

    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function getImageAttributeName(): string
    {
        return 'image';
    }

    public function getImageDirectory(): string
    {
        return 'posts';
    }

    public function updatedBy()
    {
        return $this->belongsTo(Employee::class, 'updated_by');
    }

    protected function isUserAuthenticated(): bool
    {
        return Auth::check();
    }

    /**
     * Rebuild slug_path based on the category's slug_path + post slug.
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
     * When a Post is being saved → generate slug_path from the *current* category
     *
     * After the post is persisted → make sure the column is in sync
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function (Post $post) {
            if ($post->isDirty(['slug', 'category_id'])) {
                $post->refreshSlugPath();
                $post->slug_path = $post->slug_path;
            }
        });

        static::saved(function (Post $post) {
            if ($post->wasChanged(['slug', 'category_id'])) {
                $post->refreshSlugPath();
                $post->updateQuietly(['slug_path' => $post->slug_path]);
            }
        });
    }
}
