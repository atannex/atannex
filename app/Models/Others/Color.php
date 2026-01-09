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
     * Retrieve the hex color code for the given color name.
     *
     * @param string $name The color name to look up.
     * @return string The hex code associated with the color name.
     */
    public static function hex(string $name): string
    {
        return static::where('name', $name)->value('hex');
    }

    /**
     * Selects a random hex code from all stored colors.
     *
     * Assumes there is at least one color in the table.
     *
     * @return string A hex color code (for example '#RRGGBB').
     */
    public static function randomHex(): string
    {
        $colors = static::pluck('hex')->toArray();
        return $colors[array_rand($colors)];
    }
}