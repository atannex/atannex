<?php

namespace App\Models\Pages;

use Morfaw\Supports\EnableSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ngangagah\Relations\WidgetRelation;
class Widget extends Model
{
    use EnableSlug;
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
