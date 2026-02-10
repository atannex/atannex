<?php

namespace Atannex\Concerns;

use App\Enums\Flag;
use App\Enums\Status;
use App\Models\Posts\Post;
use App\Models\Regions\Category;
use App\Models\Regions\Employee;
use App\Models\Tags\Tag;
use Atannex\Traits\HasGlobal;

trait HasResolver
{
    use HasGlobal;

    /**
     * Check if an author exists by slug.
     */
    protected function authorExists(string $slug): bool
    {
        return Employee::where('status', Status::ACTIVE)
            ->whereHas('user', fn($q) => $q->where('slug', $slug))
            ->exists();
    }

    /**
     * Check if a category exists by slug.
     */
    protected function categoryExists(string $slug): bool
    {
        return Category::where([
            ['flag', Flag::PUBLISHED],
            ['slug_path', $slug],
        ])->exists();
    }

    /**
     * Check if a tag exists by slug.
     */
    protected function tagExists(string $slug): bool
    {
        return Tag::where('slug', $slug)->exists();
    }

    /**
     * Check if a post exists by slug.
     */
    protected function postExists(string $slug): bool
    {
        return Post::published()
            ->where('slug_path', $slug)
            ->exists();
    }
}
