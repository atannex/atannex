<?php

namespace App\Models\Regions;

use Atannex\Enables\Slugging;
use Atannex\Traits\HasCleaning;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ruler extends Model
{
    use Slugging;
    use HasCleaning;
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

    /* -----------------------------------------------------------------
     |  Relationships
     | -----------------------------------------------------------------
     */

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /* -----------------------------------------------------------------
     |  Image Handling (Universal)
     | -----------------------------------------------------------------
     */

    /**
     * Return the image attributes for this model.
     */
    public function images(): array
    {
        return ['image'];
    }

    /**
     * Return the storage directory for images.
     */
    public function dir(): string
    {
        return 'rulers';
    }
}
