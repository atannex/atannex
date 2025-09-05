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
 * Includes a slug path for hierarchical URL resolution or region-specific routing.
 *
 * @package App\Models\Pivots
 */
class PostRegion extends Pivot
{
    /**
     * The table associated with the pivot model.
     *
     * @var string
     */
    protected $table = 'post_region';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'post_id',
        'region_id',
    ];

    /**
     * Get the post associated with this pivot record.
     *
     * @return BelongsTo<Post, PostRegion>
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the region associated with this pivot record.
     *
     * @return BelongsTo<Region, PostRegion>
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
