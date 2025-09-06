<?php

namespace App\Models\Pages;

<<<<<<< HEAD
use Atannex\Enables\EnableSlug;
use Atannex\Enables\EnableScope;
=======
use Atannex\Enables\Slug;
use Atannex\Enables\Scope;
>>>>>>> b90bee7 (SEO for news → news_keywords, article:section, published_time improve indexing by Google News.)
use Atannex\Relations\SectionRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use SoftDeletes;
<<<<<<< HEAD
    use EnableSlug;
    use SectionRelation;
    use EnableScope;
=======
    use Slug;
    use SectionRelation;
    use Scope;
>>>>>>> b90bee7 (SEO for news → news_keywords, article:section, published_time improve indexing by Google News.)

    protected string $slugSource = 'name';

    protected $fillable = [
        'slug',
        'name',
        'description',
        'metadata',
        'position',
        'is_active',
    ];

    protected $casts = [
        'metadata' => 'array',
        'position' => 'integer',
        'is_active' => 'boolean',
    ];

    protected function getDomIdAttribute(): string
    {
        return 'section-' . $this->pivot->id;
    }
}
