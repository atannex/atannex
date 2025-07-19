<?php

namespace App\Models\Others;

use App\Enums\Flag;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class About extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'content',
        'image',
        'video',
        'cta',
        'cta_background',
        'slug',
        'meta_title',
        'meta_description',
        'created_by',
        'updated_by',
        'flag',
    ];

    protected $casts = [
        'image' => 'array',
        'cta' => 'array',
        'flag' => Flag::class,
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}