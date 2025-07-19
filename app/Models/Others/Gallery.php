<?php

namespace App\Models\Others;

use App\Enums\Flag;
use App\Enums\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morfaw\Supports\EnableScope;

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
