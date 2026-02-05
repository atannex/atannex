<?php

namespace App\Models\Comments;

use Illuminate\Database\Eloquent\Model;
use App\Models\Guest;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\ReactionType;
use App\Models\User;

class Likeable extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'guest_id'
    ];

    /**
     * Cast attributes.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type' => ReactionType::class,
    ];

    /**
     * Get the parent likeable model (comment, post, etc.).
     */
    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who performed this reaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get only likes.
     */
    public function scopeLikes($query)
    {
        return $query->where('type', ReactionType::LIKE);
    }

    /**
     * Scope to get only dislikes.
     */
    public function scopeDislikes($query)
    {
        return $query->where('type', ReactionType::DISLIKE);
    }

    /**
     * Check if this reaction is a like.
     */
    public function isLike(): bool
    {
        return $this->type->is(ReactionType::LIKE);
    }

    /**
     * Check if this reaction is a dislike.
     */
    public function isDislike(): bool
    {
        return $this->type->is(ReactionType::DISLIKE);
    }


    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }
}
