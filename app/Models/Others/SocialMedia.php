<?php

namespace App\Models\Others;

use App\Enums\Flag;
<<<<<<< HEAD
use Atannex\Enables\EnableScope;
=======
use Atannex\Enables\Scope;
>>>>>>> b90bee7 (SEO for news → news_keywords, article:section, published_time improve indexing by Google News.)
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SocialMedia extends Model
{
    use SoftDeletes;
<<<<<<< HEAD
    use EnableScope;
=======
    use Scope;
>>>>>>> b90bee7 (SEO for news → news_keywords, article:section, published_time improve indexing by Google News.)

    protected $fillable = [
        'label',
        'url',
        'owner_type',
        'owner_id',
        'platform',
        'order',
        'flag',
        'is_global',
        'meta',
    ];

    protected $casts = [
        'is_global' => 'boolean',
        'meta' => 'array',
        'flag' => Flag::class,
    ];

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function booted()
    {
        static::saving(function ($socialMedia) {
            if ($socialMedia->is_global) {
                $socialMedia->owner_type = null;
                $socialMedia->owner_id = null;
            }
        });
    }
}
