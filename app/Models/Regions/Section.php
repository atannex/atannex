<?php

namespace App\Models\Regions;

use Atannex\Enables\HasSlug;
use Atannex\Enables\HasScope;
use Atannex\Relations\SectionRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use SoftDeletes;
    use HasSlug;
    use SectionRelation;
    use HasScope;

    protected string $slugSource = 'name';

    protected $fillable = [
        'name',
        'slug',
        'flag',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    protected function getDomIdAttribute(): string
    {
        return 'section-' . $this->pivot->id;
    }
}
