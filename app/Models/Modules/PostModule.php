<?php

namespace App\Models\Modules;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostModule extends Model
{
    use SoftDeletes;

    protected $fillable = ['module_content'];

    protected $casts = [
        'module_content' => 'array',
    ];
}
