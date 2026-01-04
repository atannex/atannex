<?php

namespace App\Models\Others;

use Atannex\Traits\HasCleaning;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class About extends Model
{
    use SoftDeletes;

    protected $table = 'abouts';

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image',
        'video_url',
        'map',
        'features',
        'story',
        'counters',
        'flag',
        'info',
        'item',
        'cta',
    ];

    protected $casts = [
        'image'     => 'array',
        'features'  => 'array',
        'story'     => 'array',
        'counters'  => 'array',
        'cta'       => 'array',
        'info'      => 'array',
    ];

    protected $dates = [
        'deleted_at',
    ];

    /* -----------------------------------------------------------------
     |  Image Handling (Array-Based)
     | -----------------------------------------------------------------
     */

    /**
     * Extract all image paths from the image array.
     */
    public function images(): array
    {
        if (!is_array($this->image)) {
            return [];
        }

        /**
         * Supports:
         * - ['path.jpg']
         * - ['src' => 'path.jpg']
         * - [['src' => 'path.jpg'], ...]
         */
        return collect($this->image)
            ->flatten(1)
            ->map(function ($item) {
                return is_array($item) ? ($item['src'] ?? null) : $item;
            })
            ->filter()
            ->values()
            ->toArray();
    }

    /**
     * Directory for About images.
     */
    public function dir(): string
    {
        return 'about';
    }
}
