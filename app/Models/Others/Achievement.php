<?php

namespace App\Models\Others;

use App\Enums\Flag;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Achievement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'image',
        'year',
        'meta_title',
        'meta_description',
        'published_at',
        'created_by',
        'updated_by',
        'flag',
    ];

    protected $casts = [
        'published_at' => 'datetime',
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
