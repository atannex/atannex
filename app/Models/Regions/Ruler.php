<?php

namespace App\Models\Regions;

use Atannex\Enables\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ruler extends Model
{
    use SoftDeletes;
    use HasSlug;

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

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'reign_start' => 'date',
        'reign_end' => 'date',
        'metadata' => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the region associated with the Fon.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
