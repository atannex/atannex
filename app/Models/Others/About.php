<?php

namespace App\Models\Others;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class About extends Model
{
    use SoftDeletes;

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
        'image' => 'array',
        'features' => 'array',
        'story' => 'array',
        'counters' => 'array',
        'cta' => 'array',
        'info' => 'array',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $dates = [
        'deleted_at',
    ];
}
