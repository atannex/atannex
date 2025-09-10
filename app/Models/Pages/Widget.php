<?php

namespace App\Models\Pages;

use Atannex\Enables\HasSlug;
use Atannex\Relations\WidgetRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Widget extends Model
{
    use HasSlug;
    use SoftDeletes;
    use WidgetRelation;

    protected string $slugSource = 'name';

    protected $fillable = [
        'slug',
        'type',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
