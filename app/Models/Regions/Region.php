<?php

namespace App\Models\Regions;

use Atannex\Enables\EnableSlug;
use Atannex\Enables\EnableScope;
use Atannex\Relations\RegionRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
    use SoftDeletes;
    use RegionRelation;
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
