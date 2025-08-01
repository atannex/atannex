<?php

namespace App\Models\Pages;

use Morfaw\Supports\EnableSlug;
use Morfaw\Supports\EnableScope;
use Illuminate\Database\Eloquent\Model;
use Ngangagah\Relations\SectionRelation;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use SoftDeletes;
    use EnableSlug;
    use SectionRelation;
    use EnableScope;

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

    public function getDomIdAttribute(): string
    {
        return 'section-' . $this->pivot->id;
    }
}
