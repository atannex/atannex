<?php

namespace App\Models\Regions;

use Atannex\Enables\Slugging;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ruler extends Model
{
    use Slugging;
    use SoftDeletes;

    protected string $slugSource = 'name';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'image',
        'dynasty',
        'title',
        'classification',
        'reign_start',
        'reign_end',
        'region_id',
        'phone',
        'email',
        'description',
        'metadata',
        'flag',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'reign_start' => 'date',
        'reign_end'   => 'date',
        'metadata'    => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
