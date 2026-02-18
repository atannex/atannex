<?php

declare(strict_types=1);

namespace App\Models\Others;

use App\Enums\Flag;
use App\Enums\Image;
use Atannex\Enables\Scoping;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use HasFactory;
    use Scoping;
    use SoftDeletes;

    /**
     * Mass-assignable attributes.
     * Ensures safe bulk assignment via create() or update().
     */
    protected $fillable = [
        'original_name',
        'type',
        'image',
        'description',
        'flag',
    ];

    /**
     * Attribute type casting.
     * Automatically converts enum fields to/from database.
     */
    protected $casts = [
        'flag' => Flag::class,
        'type' => Image::class,
    ];
}
