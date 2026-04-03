<?php

declare(strict_types=1);

namespace App\Models\Tags;

use App\Models\Pivots\PostTag;
use App\Models\Posts\Post;
use Atannex\Foundation\Concerns\GeneratesSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use GeneratesSlug;
    use SoftDeletes;

    protected string $slugMode      = self::MODE_MIXED;

    protected string $slugColumn    = 'slug';

    protected string|array $slugSource = 'name';

    protected string $slugSeparator = '-';

    protected ?int $slugMaxLength   = 100;

    protected $fillable = [
        'name',
        'description',
        'slug',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class)
            ->using(PostTag::class)
            ->withTimestamps();
    }
}
