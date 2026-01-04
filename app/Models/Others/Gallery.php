<?php

namespace App\Models\Others;

use App\Enums\Flag;
use App\Enums\Image;
use Atannex\Enables\Scoping;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use Scoping;
    use SoftDeletes;

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
     * Get the model's image attribute keys.
     *
     * @return string[] The list of attribute names that store images (e.g., ['image']).
     */
    public function images(): array
    {
        return ['image'];
    }

    /**
     * Directory for storing gallery images (used by FileUpload and HasCleaning).
     */
    public function dir(): string
    {
        return 'gallery';
    }
}