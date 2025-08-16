<?php

namespace App\Models\Posts;

use App\Contracts\Commentable;
use App\Enums\Flag;
use App\Models\Traits\Bootable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morfaw\Orchestrators\ImageCleanup;
use Morfaw\Supports\EnableScope;
use Morfaw\Supports\EnableSlug;
use Ngangagah\Relations\PostRelation;

class Post extends Model implements Commentable
{
    use SoftDeletes;
    use EnableSlug;
    use EnableScope;
    use PostRelation;
    use ImageCleanup;
    use Bootable;

    /**
     * The source attribute for slug generation.
     *
     * @var string
     */
    protected string $slugSource = 'title';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'title',
        'slug',
        'slug_path',
        'flag',
        'category_id',
        'author_id',
        'updated_by',
        'description',
        'image',
        'published_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * The model's default attribute values.
     *
     * @var array<string,mixed>
     */
    protected $attributes = [
        'flag' => Flag::DRAFT,
    ];
}
