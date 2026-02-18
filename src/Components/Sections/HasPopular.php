<?php

declare(strict_types=1);

namespace Atannex\Components\Sections;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait HasPopular
{
    /**
     * Retrieve the top popular posts.
     *
     * @param  int  $limit  The maximum number of posts to return; defaults to 5.
     * @return Collection A collection of popular Post models.
     */
    public function hasPopularPosts(int $limit = 5): Collection
    {
        return Post::query()
            ->popular($limit)
            ->get();
    }
}
