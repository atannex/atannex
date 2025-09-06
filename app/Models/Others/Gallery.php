<?php

namespace App\Models\Others;

use App\Enums\Flag;
use App\Enums\Image;
use Atannex\Enables\EnableScope;
use Atannex\Traits\Cleaning;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use SoftDeletes;
    use EnableScope;
    use Cleaning;

    protected $fillable = [
        'original_name',
        'type',
        'image',
        'description',
        'flag',
    ];

    protected $casts = [
        'flag' => Flag::class,
        'type' => Image::class,
    ];

    /**
     * Define attributes that store image paths.
     *
     * @return array<string>
     */
    protected function imageAttributes(): array
    {
        return ['image'];
    }

    /**
     * Define the storage disk for image cleanup.
     *
     * @return string
     */
    protected function imageDisk(): string
    {
        return 'public';
    }
}
