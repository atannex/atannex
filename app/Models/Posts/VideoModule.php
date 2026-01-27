<?php

declare(strict_types=1);

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VideoModule extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'video_modules';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'content',
        'images',
        'video_id',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'images' => 'array',
    ];

    /**
     * Get the video that owns the module.
     */
    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class, 'video_id');
    }
}
