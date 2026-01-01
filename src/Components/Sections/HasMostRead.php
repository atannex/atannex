<?php

declare(strict_types=1);

namespace Atannex\Components\Sections;

use App\Enums\Flag;
use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait HasMostRead
{
    /**
     * Retrieve published posts limited to the specified count.
     *
     * @param int $limit Maximum number of posts to return; defaults to 5.
     * @return Collection A collection of Post models containing up to `$limit` published posts.
     */
    public function hasMostReadPosts(int $limit = 5): Collection
    {
        return Post::query()
            ->published()
            ->take($limit)
            ->get();
    }
}