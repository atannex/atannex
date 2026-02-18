<?php

declare(strict_types=1);

namespace App\Models\Posts;

use App\Enums\Flag;
use Atannex\Enables\Scoping;
use Atannex\Enables\Slugging;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use Scoping;
    use Slugging;
    use SoftDeletes;

    /**
     * The attribute used to generate the slug.
     */
    protected string $slugSource = 'title';

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'video_url',
        'image',
        'flag',
        'published_at',
        'post_id',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'flag' => Flag::class,
        'published_at' => 'datetime',
    ];

    /**
     * --------------------------------
     * Relationships
     * --------------------------------
     */

    /**
     * A video belongs to a post.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
