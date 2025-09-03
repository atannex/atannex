<?php

namespace App\Models\Pivots;

use App\Models\Posts\Post;
use App\Models\Regions\Region;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class PostRegion
 *
 * Pivot model representing the many-to-many relationship between Posts and Regions.
 * Supports soft deletes and allows tracking which posts are associated with which regions.
 *
 * @package App\Models\Pivots
 */
class PostRegion extends Pivot
{
    use SoftDeletes;

    /**
     * The table associated with the pivot model.
     *
     * @var string
     */
    protected $table = 'post_region';

    /**
     * The attributes that should be treated as dates.
     * This is used for soft deletes (deleted_at).
     *
     * @var array<int, string>
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'post_id',   // ID of the associated post
        'region_id', // ID of the associated region
    ];

    /**
     * Get the post associated with this pivot.
     *
     * @return BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the region associated with this pivot.
     *
     * @return BelongsTo
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
