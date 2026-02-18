<?php

declare(strict_types=1);

namespace App\Models\Modules;

use App\Enums\PostType;
use App\Models\Posts\Post;
use Atannex\Traits\HasReading;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PostModule
 *
 * Represents a module (like paragraphs, heading, image, etc.) associated with a post.
 *
 *
 * @property int $id
 * @property PostType $type
 * @property array|null $video
 * @property array|null $content
 * @property int $post_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Post $post
 */
class PostModule extends Model
{
    use HasReading;
    use SoftDeletes;

    /** @var string The table associated with the model */
    protected $table = 'post_modules';

    /** @var array Mass-assignable attributes */
    protected $fillable = [
        'type',
        'video',
        'content',
        'post_id',
    ];

    /** @var array Attribute casting */
    protected $casts = [
        'type' => PostType::class,
        'content' => 'array',
        'video' => 'array',
    ];

    /**
     * Get the post that owns this module.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
