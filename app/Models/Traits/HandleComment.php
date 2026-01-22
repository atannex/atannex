<?php

namespace App\Models\Traits;


use App\Models\User;
use App\Models\Comments\Comment;
use App\Models\Pivots\CommentReaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HandleComment
{
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->latest();
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(CommentReaction::class);
    }

    public function likes(): HasMany
    {
        return $this->reactions()->where('type', 'like');
    }

    public function dislikes(): HasMany
    {
        return $this->reactions()->where('type', 'dislike');
    }

    public function scopeTopLevel(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function isReply(): bool
    {
        return $this->parent_id !== null;
    }

    public function hasReplies(): bool
    {
        return $this->reply_count > 0;
    }

    public function getAuthorNameAttribute(): string
    {
        return $this->user?->name
            ?? $this->guest_name
            ?? __('Guest');
    }

    public function getAllRepliesAttribute(): Collection
    {
        return $this->replies ?? collect();
    }

    public function markEdited(): void
    {
        $this->forceFill(['edited_at' => now()])->save();
    }

    public function incrementReplyCount(int $by = 1): void
    {
        $this->increment('reply_count', $by);
    }

    public function addReply(Comment $reply): Comment
    {
        $reply->forceFill([
            'parent_id' => $this->id,
        ])->save();

        $this->incrementReplyCount();

        return $reply;
    }

    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function isDislikedBy(User $user): bool
    {
        return $this->dislikes()->where('user_id', $user->id)->exists();
    }

    public function like(User $user): void
    {
        $this->reactions()->updateOrCreate(
            ['user_id' => $user->id],
            ['type' => 'like']
        );

        $this->refreshReactionCounters();
    }

    public function dislike(User $user): void
    {
        $this->reactions()->updateOrCreate(
            ['user_id' => $user->id],
            ['type' => 'dislike']
        );

        $this->refreshReactionCounters();
    }

    public function removeReaction(User $user): void
    {
        $this->reactions()
            ->where('user_id', $user->id)
            ->delete();

        $this->refreshReactionCounters();
    }

    protected function refreshReactionCounters(): void
    {
        $this->forceFill([
            'like_count'    => $this->likes()->count(),
            'dislike_count' => $this->dislikes()->count(),
        ])->save();
    }
}
