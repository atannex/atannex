<?php

declare(strict_types=1);

namespace App\Models\Comments;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Rating extends Model
{
    protected $fillable = [
        'user_id',
        'visitor_key',
        'session_id',
        'ip_address',
        'rating',
        'comment',
        'visitor_key',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Polymorphic relation to any rateable model.
     */
    public function rateable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * User who submitted the rating (nullable for guests).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
