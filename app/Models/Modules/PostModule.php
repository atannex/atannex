<?php

declare(strict_types=1);

namespace App\Models\Modules;

use App\Enums\PostType;
use App\Models\Posts\Post;
use Atannex\Contracts\HasImages;
use Atannex\Traits\HasReading;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostModule extends Model implements HasImages
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

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    /**
     * Extract all image paths from content JSON.
     */
    public function images(): array
    {
        return ['image'];
    }

    /**
     * Directory for storing module images.
     */
    public function dir(): string
    {
        return 'modules';
    }
}
