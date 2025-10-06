<?php

namespace App\Models\Others;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class About extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * (Optional if you follow Laravel's naming convention)
     */
    protected $table = 'abouts';

    /**
     * The attributes that are mass assignable.
     *
     * Helps prevent mass assignment vulnerabilities.
     */
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
        'cta'
    ];

    /**
     * The attributes that should be cast.
     *
     * Ensures proper handling of JSON and arrays.
     */
    protected $casts = [
        'image'     => 'array',
        'features'  => 'array',
        'story'     => 'array',
        'counters'  => 'array',
        'cta'       => 'array',
        'info'      => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     */
    protected $dates = [
        'deleted_at',
    ];
}
