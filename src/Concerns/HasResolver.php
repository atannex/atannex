<?php

namespace Atannex\Concerns;

use App\Enums\Flag;
use App\Enums\Status;
use App\Models\Docs\Document;
use App\Models\Posts\Post;
use App\Models\Regions\Category;
use App\Models\Regions\Employee;
use App\Models\Tags\Tag;
use Illuminate\Database\Eloquent\Builder;

trait HasResolver
{
    /*
    |--------------------------------------------------------------------------
    | Public Resolvers
    |--------------------------------------------------------------------------
    */

    /**
     * Determine whether an active author exists by slug.
     */
    protected function authorExists(string $slug): bool
    {
        return Employee::query()
            ->where('status', Status::ACTIVE)
            ->whereHas('user', fn (Builder $query) => $query->where('slug', $slug)
            )
            ->exists();
    }

    /**
     * Check if a document exists without triggering a 404.
     */
    protected function documentExists(string $slug): bool
    {
        return Document::query()
            ->flagged(Flag::PUBLISHED)
            ->where('slug_path', $slug)
            ->exists();
    }

    /**
     * Determine whether a published category exists by slug path.
     */
    protected function categoryExists(string $slug): bool
    {
        return Category::query()
            ->where('flag', Flag::PUBLISHED)
            ->where('slug_path', $slug)
            ->exists();
    }

    /**
     * Determine whether a tag exists by slug.
     */
    protected function tagExists(string $slug): bool
    {
        return Tag::query()
            ->where('slug', $slug)
            ->exists();
    }

    /**
     * Determine whether a published post exists by slug path.
     */
    protected function postExists(string $slug): bool
    {
        return Post::query()
            ->published()
            ->where('slug_path', $slug)
            ->exists();
    }
}
