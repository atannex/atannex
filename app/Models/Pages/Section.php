<?php

namespace App\Models\Pages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morfaw\Supports\EnableScope;
use Morfaw\Supports\EnableSlug;
use Ngangagah\Relations\Section as RelationsSection;

class Section extends Model
{
    use SoftDeletes;
    use EnableSlug;
    use RelationsSection;
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
