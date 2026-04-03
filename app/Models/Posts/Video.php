<?php

declare(strict_types=1);

namespace App\Models\Posts;

use App\Enums\Flag;
use Atannex\Enables\Scoping;
use Atannex\Foundation\Concerns\GeneratesSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use Scoping;
    use SoftDeletes;
    use GeneratesSlug;

    protected string $slugMode      = self::MODE_RANDOM;

    protected string $slugColumn    = 'slug';

    protected string|array $slugSource = 'title';

    protected string $slugSeparator = '-';

    protected ?int $slugMaxLength   = 100;

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

    protected $casts = [
        'flag' => Flag::class,
        'published_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
