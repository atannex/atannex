<?php

namespace App\Models\Others;

use App\Enums\Flag;
use App\Enums\Image;
use Atannex\Enables\EnableScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use SoftDeletes;
    use EnableScope;

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
}
