<?php

namespace App\Models\Others;

use App\Enums\Flag;
use Atannex\Enables\EnableScope;
use Illuminate\Database\Eloquent\Model;
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

    protected static function booted()
    {
        static::saving(function ($socialMedia) {
            if ($socialMedia->is_global) {
                $socialMedia->owner_type = null;
                $socialMedia->owner_id = null;
            }
        });
    }
}
