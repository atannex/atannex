<?php

declare(strict_types=1);

namespace App\Models\Comments;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class View extends Model
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
        'user_agent',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Polymorphic parent (Post, Comment, Product, etc.)
     */
    public function viewable(): MorphTo
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
