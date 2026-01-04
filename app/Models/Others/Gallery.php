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
     * Return the image attributes for this model.
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
