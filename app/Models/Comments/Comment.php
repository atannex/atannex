<?php

declare(strict_types=1);

namespace App\Models\Comments;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'commentable_type',
        'commentable_id',
        'parent_id',
        'root_id',
        'body',
    ];

    // ── Relationships ─────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /** Immediate parent (top-level comment OR another reply) */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /** The top-level comment this belongs to */
    public function root(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'root_id');
    }

    /** Direct children of THIS comment (one level down) */
    public function directReplies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')
            ->with(['user', 'likes', 'directReplies.user', 'directReplies.likes'])
            ->withCount('likes')
            ->oldest();
    }

    /** Users who liked this comment */
    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'comment_likes')->withTimestamps();
    }

    // ── Scopes ────────────────────────────────────────────────

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }
}
