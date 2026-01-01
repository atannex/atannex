<?php

declare(strict_types=1);

namespace Atannex\Components\Sections;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait HasPopular
{
    /**
     * Get the top popular posts using the Post model's scopePopular().
     */
    public function hasPopularPosts(int $limit = 5): Collection
    {
        return Post::query()
            ->popular($limit)
            ->get();
    }
}
