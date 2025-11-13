<?php

namespace App\Models\Others;

use App\Enums\Flag;
use Atannex\Enables\Scoping;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialMedia extends Model
{
    use Scoping;
    use SoftDeletes;

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
