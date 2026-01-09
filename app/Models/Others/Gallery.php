<?php

declare(strict_types=1);

namespace App\Models\Others;

use App\Enums\Flag;
use App\Enums\Image;
use Atannex\Enables\Scoping;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gallery extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Scoping;

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
<<<<<<< HEAD

    /**
     * List model attributes that are treated as image files.
     *
     * @return string[] Array of attribute names that are managed as image files.
     */
    public function images(): array
    {
        return ['image'];
    }

    /**
     * Get storage directory path for this gallery's images.
     *
     * The path uses the model's primary key or `temp` when no key exists, and includes the current year and month.
     *
     * @return string The directory path in the form "gallery/{id_or_temp}/YYYY/MM".
     */
    public function dir(): string
    {
        return sprintf(
            '%s/%s/%s',
            self::BASE_DIR,
            $this->getKey() ?? 'temp',
            now()->format('Y/m')
        );
    }
}
=======
}
>>>>>>> development
