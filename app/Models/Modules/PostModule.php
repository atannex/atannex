<?php

declare(strict_types=1);

namespace App\Models\Modules;

use App\Enums\PostType;
use App\Events\PostContentChanged;
use App\Models\Posts\Post;
use Atannex\Traits\HasReading;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class PostModule extends Model
{
    use HasReading;
    use SoftDeletes;

    protected $table = 'post_modules';

    protected $fillable = [
        'type',
        'video',
        'content',
        'post_id',
    ];

    protected $casts = [
        'type'    => PostType::class,
        'content' => 'array',
        'video'   => 'array',
    ];

    /* -----------------------------------------------------------------
     |  Relationships
     | -----------------------------------------------------------------
     */

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    /* -----------------------------------------------------------------
     |  Domain Event Dispatching (STRICT & GUARDED)
     | -----------------------------------------------------------------
     */

    protected static function booted(): void
    {
        static::updated(function (PostModule $module) {
            if (! $module->hasRenderableChanges()) {
                return;
            }

            $post = $module->post()->withTrashed()->first();

            if ($post) {
                self::dispatchPostContentChanged($post);
            }
        });

        static::deleted(function (PostModule $module) {
            $post = $module->post()->withTrashed()->first();

            if ($post) {
                self::dispatchPostContentChanged($post);
            }
        });

        static::restored(function (PostModule $module) {
            $post = $module->post()->withTrashed()->first();

            if ($post) {
                self::dispatchPostContentChanged($post);
            }
        });
    }

    /**
     * Determine if module changes affect rendered post output.
     */
    protected function hasRenderableChanges(): bool
    {
        return $this->wasChanged([
            'type',
            'content',
            'video',
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
}
