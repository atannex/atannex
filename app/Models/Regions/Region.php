<?php

namespace App\Models\Regions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morfaw\Supports\EnableScope;
use Morfaw\Supports\EnableSlug;
use Ngangagah\Relations\Region as RelationsRegion;

class Region extends Model
{
    use SoftDeletes;
    use RelationsRegion;
    use EnableSlug;
    use EnableScope;

    protected string $slugSource = 'name';

    protected $fillable = [
        'name',
        'flag',
        'slug',
        'type',
        'logo',
        'description',
        'parent_id',
    ];

    /**
     * Casts.
     */
    protected $casts = [
        'flag' => 'string',
    ];
}
