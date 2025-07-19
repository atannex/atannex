<?php

namespace App\Models\Others;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hex',
    ];

    /**
     * Get hex code by color name without caching.
     *
     * @param string $name
     * @param string $default
     * @return string
     */
    public static function hex(string $name, string $default = '#CCCCCC'): string
    {
        return static::where('name', $name)->value('hex') ?? $default;
    }

    /**
     * Get a random hex code from all colors without caching.
     *
     * @param string $fallback
     * @return string
     */
    public static function randomHex(string $fallback = '#CCCCCC'): string
    {
        $colors = static::pluck('hex')->toArray();

        return !empty($colors) ? $colors[array_rand($colors)] : $fallback;
    }
}
