<?php

namespace App\Models\Posts;

use App\Enums\Flag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morfaw\Orchestrators\ImageCleanup;
use Morfaw\Supports\EnablePath;
use Morfaw\Supports\EnableScope;
use Morfaw\Supports\EnableSlug;
use Ngangagah\Relations\PostRelation;

class Post extends Model
{
    use SoftDeletes;
    use EnableSlug;
    use EnableScope;
    use PostRelation;
    use EnablePath;
    use ImageCleanup;

    protected string $slugSource = 'title';

    protected $fillable = [
        'title',
        'slug',
        'flag',
        'category_id',
        'author_id',
        'updated_by',
        'description',
        'image',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected $attributes = [
        'flag' => Flag::DRAFT,
    ];
}
