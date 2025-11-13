<?php

namespace App\Models\Regions;

use Atannex\Enables\Scoping;
use Atannex\Enables\Slugging;
use Atannex\Relations\SectionRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use Scoping;
    use SectionRelation;
    use Slugging;
    use SoftDeletes;

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
        return 'section-'.$this->pivot->id;
    }
}
