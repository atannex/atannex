<?php

namespace App\Models\Others;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = [
        'name',
        'hex',
    ];

    /**
     * Get hex code by color name without caching.
     */
    public static function hex(string $name, string $default = '#CCCCCC'): string
    {
        return static::where('name', $name)->value('hex') ?? $default;
    }

    /**
     * Get a random hex code from all colors without caching.
     */
    public static function randomHex(string $fallback = '#CCCCCC'): string
    {
        $colors = static::pluck('hex')->toArray();

        return !empty($colors) ? $colors[array_rand($colors)] : $fallback;
    }
}
