<?php

namespace Atannex\Builders;

use App\Models\Tags\Tag;
use App\Models\Pivots\PostTag;


trait TagBuilder
{
     /**
     * Boot method to handle cascading soft deletes and pivot slug updates.
     */
    protected static function booted(): void
    {
        // Cascade soft delete to child tags
        static::deleting(function (Tag $tag) {
            $tag->children()->delete();
        });

        // Update related pivot slug paths after saving
        static::saved(function (Tag $tag) {
            if ($tag->isDirty('slug')) {
                // Efficiently batch update pivot slug paths
                PostTag::where('tag_id', $tag->id)
                    ->get()
                    ->each(function (PostTag $pivot) {
                        $pivot->slug_path = $pivot->buildDynamicSlugPath();
                        $pivot->saveQuietly();
                    });
            }
        });
    }

}
