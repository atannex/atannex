<?php

declare(strict_types=1);

namespace App\Models\Others;

use App\Enums\Flag;
use App\Enums\Image;
use App\Enums\TailwindColor;
use Atannex\Enables\Scoping;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use Scoping, SoftDeletes;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'title',
        'type',
        'image',
        'description',
        'flag',
        'color',
        'order',
    ];

    /**
     * Hidden attributes
     */
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'flag'  => Flag::class,
        'type'  => Image::class,
        'color' => TailwindColor::class,
    ];

    /**
     * Default attribute values
     */
    protected $attributes = [
        'flag'  => Flag::PENDING_REVIEW,
        'type'  => Image::LOGO,
        'color' => TailwindColor::BLUE,
        'order' => 0,
    ];

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('flag', Flag::PUBLISHED);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order')->latest();
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image);
    }

    public function getColorClassAttribute(): string
    {
        $color = $this->color instanceof TailwindColor
            ? $this->color->value
            : $this->color;

        return "text-{$color}-300";
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isPublished(): bool
    {
        return $this->flag === Flag::PUBLISHED;
    }
}
