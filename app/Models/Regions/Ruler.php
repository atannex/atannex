<?php

namespace App\Models\Regions;

use Atannex\Foundation\Concerns\GeneratesSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ruler extends Model
{
    use GeneratesSlug;
    use SoftDeletes;

    protected string $slugMode      = self::MODE_RANDOM;

    protected string $slugColumn    = 'slug';

    protected string|array $slugSource = 'name';

    protected string $slugSeparator = '-';

    protected ?int $slugMaxLength   = 100;

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

    protected $casts = [
        'reign_start' => 'date',
        'reign_end' => 'date',
        'metadata' => 'array',
    ];

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
