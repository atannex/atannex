<?php

namespace App\Models\Modules;

use App\Enums\PostType;
use App\Models\Posts\Post;
use Atannex\Traits\HasReading;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostModule extends Model
{
    use HasReading;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'post_modules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'video',
        'content',
        'post_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type'    => PostType::class,
        'content' => 'array',
        'video' => 'array',
    ];

    /**
     * Get the post that owns the post module.
     *
     * @return BelongsTo<Post, PostModule>
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
