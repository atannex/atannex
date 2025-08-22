<?php

namespace App\Models\Modules;

use App\Models\Posts\Post;
use Atannex\Traits\Reading;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostModule extends Model
{
    use SoftDeletes;
    use Reading;

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
        'module_content',
        'post_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'module_content' => 'array',
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
