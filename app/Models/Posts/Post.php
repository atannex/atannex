<?php

declare(strict_types=1);

namespace App\Models\Posts;

use App\Contracts\Commentable;
use App\Events\PostContentChanged;
use App\Models\Regions\Employee;
use Atannex\Concerns\HasBreaking;
use Atannex\Enables\Scoping;
use Atannex\Enables\Slugging;
use Atannex\Relations\PostRelation;
use Atannex\Traits\HasCleaning;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Post extends Model implements Commentable
{
    use HasBreaking;
    use HasCleaning;
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
        'breaking_until' => 'datetime',
        'feature_until'  => 'datetime',
        'metadata'       => 'array',
        'is_breaking'    => 'boolean',
    ];

    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    /* -----------------------------------------------------------------
     |  Relations / Helpers
     | -----------------------------------------------------------------
     */

    public function updatedBy()
    {
        return $this->belongsTo(Employee::class, 'updated_by');
    }

    protected function isUserAuthenticated(): bool
    {
        return Auth::check();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /* -----------------------------------------------------------------
     |  Slug Path Handling (Safe & Silent)
     | -----------------------------------------------------------------
     */

    public function refreshSlugPath(): void
    {
        $category = $this->category()
            ->withoutGlobalScopes()
            ->select(['id', 'slug_path'])
            ->first();

        $this->slug_path = trim(
            ($category->slug_path ?? '') . '/' . $this->slug,
            '/'
        );
    }

    protected static function boot(): void
    {
        parent::boot();

        // Before save: recalc slug_path if needed
        static::saving(function (Post $post) {
            if ($post->isDirty(['slug', 'category_id'])) {
                $post->refreshSlugPath();
            }
        });

        // After save: persist slug_path quietly (NO events)
        static::saved(function (Post $post) {
            if ($post->wasChanged(['slug', 'category_id'])) {
                $post->updateQuietly([
                    'slug_path' => $post->slug_path,
                ]);
            }
        });
    }

    /* -----------------------------------------------------------------
     |  Domain Event Dispatching (STRICT & GUARDED)
     | -----------------------------------------------------------------
     */

    protected static function booted(): void
    {
        static::updated(function (Post $post) {
            if (! $post->hasContentChanged()) {
                return;
            }

            self::dispatchPostContentChanged($post);
        });

        static::deleted(
            fn(Post $post) =>
            self::dispatchPostContentChanged($post)
        );

        static::restored(
            fn(Post $post) =>
            self::dispatchPostContentChanged($post)
        );
    }

    /**
     * Determine if the update affects the rendered post content.
     */
    protected function hasContentChanged(): bool
    {
        return $this->wasChanged([
            'title',
            'slug',
            'slug_path',
            'description',
            'image',
            'metadata',
            'published_at',
            'is_breaking',
            'breaking_until',
            'feature_until',
            'category_id',
        ]);
    }

    /**
     * Dispatch the PostContentChanged event with debounce protection.
     */
    protected static function dispatchPostContentChanged(Post $post): void
    {
        Cache::lock(
            "post-content-changed:{$post->id}",
            2
        )->get(
            fn() =>
            PostContentChanged::dispatch($post)
        );
    }

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
}
