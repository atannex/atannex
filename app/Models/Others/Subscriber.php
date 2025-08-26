<?php

namespace App\Models\Others;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = ['email', 'token', 'confirmed'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subscription) {
            $subscription->token = Str::random(32);
        });
    }

    protected $casts = [
    'confirmed' => 'boolean',
];
}
