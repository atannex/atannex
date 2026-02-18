<?php

declare(strict_types=1);

namespace App\Models\Comments;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Share extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'user_id',
        'visitor_key',
        'platform',
        'session_id',
        'ip_address',
        'user_agent',
        'share_count',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Polymorphic parent (Post, Comment, Product, etc.)
     */
    public function shareable(): MorphTo
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
