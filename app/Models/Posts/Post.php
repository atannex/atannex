<?php

declare(strict_types=1);

namespace App\Models\Posts;

use App\Enums\Flag;
use App\Models\Comments\Comment;
use App\Models\Modules\PostModule;
use App\Models\Pivots\PostTag;
use App\Models\Regions\Category;
use App\Models\Regions\Employee;
use App\Models\Regions\Region;
use App\Models\Tags\Tag;
use App\Models\Traits\HasComments;
use App\Models\Traits\HasRatings;
use App\Models\Traits\HasReaction;
use App\Models\Traits\HasShares;
use App\Models\Traits\HasViews;
use Atannex\Enables\Scoping;
use Atannex\Foundation\Concerns\GeneratesSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasComments;
    use HasRatings;
    use HasReaction;
    use HasShares;
    use HasViews;
    use Scoping;
    use SoftDeletes;
    use GeneratesSlug;

    protected string $slugMode      = self::MODE_RANDOM;

    protected string $slugColumn    = 'slug';

    protected string|array $slugSource = 'title';

    protected string $slugSeparator = '-';

    protected ?int $slugMaxLength   = 100;

    protected $fillable = [
        'title',
        'slug',
        'slug_path',
        'category_id',
        'region_id',
        'author_id',
        'updated_by',
        'description',
        'image',
        'published_at',

        'is_breaking',
        'breaking_at',
        'breaking_expires',

        'is_editor_pick',
        'editor_pick_at',
        'editor_pick_expires',
    ];

    protected $casts = [
        'published_at'       => 'datetime',
        'is_breaking'        => 'boolean',
        'breaking_at'        => 'datetime',
        'breaking_expires'   => 'datetime',
        'is_editor_pick'     => 'boolean',
        'editor_pick_at'     => 'datetime',
        'editor_pick_expires' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function applySlugScope(Builder $query): Builder
    {
        return $query->where('region_id', $this->region_id);
    }

    public function refreshSlugPath(): void
    {
        $category = $this->category()
            ->withoutGlobalScopes()
            ->select(['id', 'slug_path'])
            ->first();

        $categoryPath = $category?->slug_path ?? '';

        $this->slug_path = trim("{$categoryPath}/{$this->slug}", '/');
    }

    public function setSlugAttribute($value): void
    {
        $this->attributes['slug'] = $value;

        if (!empty($this->attributes['category_id'])) {
            $this->refreshSlugPath();
        }
    }

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (Post $post) {
            if (Auth::check() && Auth::user()->employee) {
                $post->updated_by = Auth::user()->employee->id;
            }

            if ($post->isDirty('category_id') && $post->category_id) {
                $post->refreshSlugPath();
            }
        });

        static::created(function (Post $post) {
            if ($post->category_id && empty($post->slug_path)) {
                $post->refreshSlugPath();
                $post->saveQuietly();
            }
        });
    }

    public function video(): HasMany
    {
        return $this->hasMany(Video::class)
            ->whereIn('flag', [Flag::APPROVED, Flag::PUBLISHED]);
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'author_id');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'updated_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'updated_by')->withTrashed();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag')
            ->using(PostTag::class)
            ->withTimestamps();
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->whereNull('parent_id')
            ->latest();
    }

    public function module(): HasOne
    {
        return $this->hasOne(PostModule::class);
    }

    protected function resolveVisitorKey(): string
    {
        if (Auth::check()) {
            return 'user_' . Auth::id();
        }

        $visitorId = request()->cookie('visitor_id');

        if (!$visitorId) {
            $visitorId = (string) Str::uuid();
            cookie()->queue(cookie('visitor_id', $visitorId, 60 * 24 * 365));
        }

        return 'guest_' . $visitorId;
    }

    protected function isUserAuthenticated(): bool
    {
        return Auth::check();
    }
}
