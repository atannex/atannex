<?php

namespace App\Models\Pages;

<<<<<<< HEAD
use Atannex\Enables\EnableSlug;
=======
use Atannex\Enables\Slug;
>>>>>>> b90bee7 (SEO for news → news_keywords, article:section, published_time improve indexing by Google News.)
use Atannex\Relations\WidgetRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Widget extends Model
{
<<<<<<< HEAD
    use EnableSlug;
=======
    use Slug;
>>>>>>> b90bee7 (SEO for news → news_keywords, article:section, published_time improve indexing by Google News.)
    use SoftDeletes;
    use WidgetRelation;

    protected string $slugSource = 'name';

    protected $fillable = [
        'slug',
        'type',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
