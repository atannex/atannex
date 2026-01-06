<?php

declare(strict_types=1);

namespace App\Models\Others;

use App\Enums\Flag;
use App\Enums\Image;
use Atannex\Contracts\HasImages;
use Atannex\Enables\Scoping;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
|--------------------------------------------------------------------------
| Gallery Model
|--------------------------------------------------------------------------
|
| Represents the gallery entity that stores images with associated metadata.
| Implements the HasImages contract for compatibility with the universal
| ImageObserver, ensuring old files are cleaned and new files are stored
| correctly. Designed to work seamlessly with Filament Admin, API uploads,
| and standard Eloquent workflows.
|
| Traits:
| - HasFactory: provides factory support for testing and seeding.
| - SoftDeletes: allows safe deletion without losing historical records.
| - Scoping: adds custom query scopes for filtering and scoping.
|
| Enums:
| - Flag: represents status or visibility flags for gallery items.
| - Image: represents type-specific handling for images.
|
| Notes:
| - The `dir()` method generates the storage path, handling temporary
|   files for new models (pre-ID) and ensuring organized year/month storage.
| - The `images()` method declares which attributes are treated as image
|   files by the observer.
*/

class Gallery extends Model implements HasImages
{
    use HasFactory;
    use SoftDeletes;
    use Scoping;

    /**
     * Base directory for all gallery uploads.
     * Files are stored under: gallery/{id}/YYYY/MM
     */
    private const BASE_DIR = 'gallery';

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