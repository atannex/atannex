<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\Comments\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Add this trait to any model that should support comments.
 *
 * Usage:
 *   class Post extends Model {
 *       use HasComments;
 *   }
 *
 * Then in your view:
 *   <livewire:comments-section :model="$post" />
 */
trait HasComments
{
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function topLevelComments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')
                    ->whereNull('parent_id');
    }

    public function commentsCount(): int
    {
        return $this->comments()->count();
    }
}
