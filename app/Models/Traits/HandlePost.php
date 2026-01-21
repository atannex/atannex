<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Auth;
use App\Models\Posts\Post;

/**
 * Trait PostRelation
 *
 * Defines all Eloquent relationships and slug logic for the Post model.
 */
trait HandlePost
{
    /**
     * Check if user is authenticated.
     */
    protected function isUserAuthenticated(): bool
    {
        return Auth::check();
    }

    /**
     * Recalculates the slug_path based on category and slug.
     */
    public function refreshSlugPath(): void
    {
        $category = $this->category()
            ->withoutGlobalScopes()
            ->select(['id', 'slug_path'])
            ->first();

        $this->slug_path = trim(
            ($category->slug_path ?? '') . '/' . $this->slug,
            '/'
        );
    }

    /**
     * Automatically refresh slug_path when slug or category changes.
     */
    public function setSlugAttribute($value): void
    {
        $this->attributes['slug'] = $value;

        if (isset($this->attributes['category_id'])) {
            $this->refreshSlugPath();
        }
    }

    /**
     * Boot the model and attach saving events for category changes and updated_by.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (Post $post) {
            // Refresh slug path if category changed
            if ($post->isDirty('category_id')) {
                $post->refreshSlugPath();
            }

            // Set updated_by to current user's employee ID
            if (Auth::check() && Auth::user()->employee) {
                $post->updated_by = Auth::user()->employee->id;
            }
        });
    }
}
