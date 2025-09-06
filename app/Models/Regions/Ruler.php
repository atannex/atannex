<?php

namespace App\Models\Regions;

use App\Enums\Flag;
<<<<<<< HEAD
use Atannex\Enables\EnableSlug;
=======
use Atannex\Enables\Slug;
>>>>>>> b90bee7 (SEO for news → news_keywords, article:section, published_time improve indexing by Google News.)
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ruler extends Model
{
    use SoftDeletes;
<<<<<<< HEAD
    use EnableSlug;
=======
    use Slug;
>>>>>>> b90bee7 (SEO for news → news_keywords, article:section, published_time improve indexing by Google News.)

    protected string $slugSource = 'name';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'image',
        'traditional_title',
        'region_id',
        'rank',
        'reign_start',
        'reign_end',
        'description',
        'flag',
        'metadata',
        'dynasty',
        'phone',
        'email',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'reign_start' => 'date',
        'reign_end' => 'date',
        'metadata' => 'array',
        'flag' => Flag::class,
    ];

    /**
     * Get the region associated with the Fon.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Scope for filtering by region.
     */
    protected function scopeOfRegion($query, int $regionId)
    {
        return $query->where('region_id', $regionId);
    }

    /**
     * Scope to filter active reigns (no end date).
     */
    protected function scopeCurrentlyReigning($query)
    {
        return $query->whereNull('reign_end');
    }

    /**
     * Get full title including traditional title and name.
     */
    protected function getFullTitleAttribute(): string
    {
        return trim(sprintf('%s %s', $this->traditional_title, $this->name));
    }

    /**
     * Get status flag label (optional if you use enums with labels).
     */
    protected function getFlagLabelAttribute(): string
    {
        return $this->flag->label();
    }
}
