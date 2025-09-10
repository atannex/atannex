<?php

namespace App\Models\Pages;

use Atannex\Enables\HasSlug;
use Atannex\Enables\HasScope;
use Atannex\Enables\EnableSlug;
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
