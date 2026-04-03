<?php

namespace App\Models\Others;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = [
        'name',
        'hex',
    ];

    public static function hex(string $name): string
    {
        return static::where('name', $name)->value('hex');
    }

    public static function randomHex(): string
    {
        $colors = static::pluck('hex')->toArray();

        return $colors[array_rand($colors)];
    }
}
