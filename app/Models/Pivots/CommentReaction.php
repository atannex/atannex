<?php

namespace App\Models\Pivots;

use App\Models\User;
use App\Models\Comments\Comment;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentReaction extends Pivot
{
    protected $fillable = [
        'comment_id',
        'user_id',
        'type',
    ];

    protected $casts = [
        'type' => 'string',
    ];

    /**
     * Get the comment that was reacted to.
     */
    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    /**
     * Get the authenticated user who reacted.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

