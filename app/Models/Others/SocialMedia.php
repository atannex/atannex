<?php

namespace App\Models\Others;

use App\Enums\Flag;
use Morfaw\Supports\EnableScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SocialMedia extends Model
{
    use SoftDeletes;
    use EnableScope;

    protected $fillable = [
        'label',
        'url',
        'owner_type',
        'owner_id',
        'platform',
        'order',
        'flag',
        'is_global',
        'meta',
    ];

    protected $casts = [
        'is_global' => 'boolean',
        'meta' => 'array',
        'flag' => Flag::class,
    ];

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeGlobal(Builder $query): Builder
    {
        return $query->where('is_global', true);
    }

    public function scopeForOwner(Builder $query, Model $owner): Builder
    {
        return $query->where('owner_type', get_class($owner))
            ->where('owner_id', $owner->getKey());
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }
}
