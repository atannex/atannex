<?php

namespace App\Models\Others;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    protected $table = 'contacts';

    protected $fillable = [
        'name',
        'email',
        'number',
        'subject',
        'message',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];
}
