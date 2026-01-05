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
     * Returns the list of attributes that represent image files.
     * Used by ImageObserver to manage file uploads and deletions.
     *
     * @return string[]
     */
    public function images(): array
    {
        return ['image'];
    }

    /**
     * Returns the directory path where gallery images should be stored.
     * Handles new records (pre-ID) using 'temp' to prevent broken paths.
     *
     * Example:
     * - Before model save: gallery/temp/2026/01
     * - After model save: gallery/12/2026/01
     *
     * @return string
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
