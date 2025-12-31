<?php

declare(strict_types=1);

namespace App\Models\Modules;

use App\Enums\PostType;
use App\Models\Posts\Post;
use Atannex\Traits\HasCleaning;
use Atannex\Traits\HasReading;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostModule extends Model
{
    use HasReading;
    use HasCleaning;
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
     |  Image Handling for Content
     | -----------------------------------------------------------------
     */

    /**
     * Extract all image paths from content JSON.
     */
    public function images(): array
    {
        if (!is_array($this->content)) {
            return [];
        }

        $images = [];

        foreach ($this->content as $block) {
            if (!isset($block['type'], $block['data'])) {
                continue;
            }

            // Image block
            if ($block['type'] === 'image' && !empty($block['data']['src'])) {
                $images[] = $block['data']['src'];
            }

            // Video covers (optional)
            if ($block['type'] === 'video_grid' && !empty($block['data']['items'])) {
                foreach ($block['data']['items'] as $item) {
                    if (!empty($item['cover'])) {
                        $images[] = $item['cover'];
                    }
                }
            }
        }

        return $images;
    }

    /**
     * Directory for storing module images.
     */
    public function dir(): string
    {
        return 'modules';
    }
}
