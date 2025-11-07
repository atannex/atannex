<?php

namespace App\Models\Pivots;

use App\Models\Posts\Post;
use App\Models\Regions\Region;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class PostRegion
 *
 * Pivot model representing the many-to-many relationship between Posts and Regions.
 * Supports soft deletes and optional published filtering.
 * Includes slug path for hierarchical URL resolution or region-specific routing.
 */
class PostRegion extends Pivot
{
    protected $table = 'post_region';

    protected $fillable = [
        'post_id',
        'region_id',
    ];

    /**
     * Get the post associated with this pivot record.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the region associated with this pivot record.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
