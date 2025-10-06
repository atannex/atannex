<?php

namespace App\Models\Others;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Contact
 *
 * Represents a Contact model for storing contact form submissions in the database.
 * Extends Laravel's Eloquent Model and uses soft deletion for record management.
 *
 * @package App\Models\Others
 */
class Contact extends Model
{
    use SoftDeletes;

    /**
     * The database table associated with the model.
     *
     * @var string
     */
    protected $table = 'contacts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'number',
        'subject',
        'message',
    ];

    /**
     * The attributes that should be treated as dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];
}
