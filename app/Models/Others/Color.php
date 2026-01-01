<?php

namespace App\Models\Others;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = [
        'name',
        'hex',
    ];

    /**
     * Get hex code by color name.
     * Assumes the color always exists.
     */
    public static function hex(string $name): string
    {
        return static::where('name', $name)->value('hex');
    }

    /**
     * Get a random hex code from all colors.
     * Assumes there is always at least one color in the table.
     */
    public static function randomHex(): string
    {
        $colors = static::pluck('hex')->toArray();
        return $colors[array_rand($colors)];
    }
}
