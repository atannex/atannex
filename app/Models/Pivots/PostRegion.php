<?php

namespace App\Models\Pivots;

use App\Models\Posts\Post;
use App\Models\Regions\Region;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PostRegion extends Pivot
{
    use SoftDeletes;

    protected $table = 'post_region';

    protected $dates = ['deleted_at'];

    protected $fillable = ['post_id', 'region_id'];

    /**
     * Get the post associated with this pivot.
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the region associated with this pivot.
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}