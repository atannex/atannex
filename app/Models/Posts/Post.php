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

/**
 * Class Post
 *
 * Represents a news post or article.
 *
 * @package App\Models\Posts
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int $flag
 * @property int $category_id
 * @property int $author_id
 * @property int|null $updated_by
 * @property string|null $description
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Post extends Model
{
    use SoftDeletes, EnableSlug, EnableScope, PostRelation, EnablePath, ImageCleanup;

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
