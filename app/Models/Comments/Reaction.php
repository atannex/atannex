<?php

declare(strict_types=1);

namespace App\Models\Comments;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\ReactionType;
use App\Models\User;

class Reaction extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'user_id',
        'visitor_key',
        'session_id',
        'ip_address',
        'type',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'type' => ReactionType::class,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Polymorphic parent (Post, Comment, etc.)
     */
    public function reactable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Authenticated user (nullable for guests)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
