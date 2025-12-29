<?php

declare(strict_types=1);

namespace App\Models\Posts;

use App\Contracts\Commentable;
use App\Models\Regions\Employee;
use Atannex\Concerns\HasBreaking;
use Atannex\Enables\Scoping;
use Atannex\Enables\Slugging;
use Atannex\Relations\PostRelation;
use Atannex\Traits\HasCleaning;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

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
