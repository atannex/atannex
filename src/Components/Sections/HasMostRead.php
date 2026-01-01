<?php

declare(strict_types=1);

namespace Atannex\Components\Sections;

use App\Enums\Flag;
use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait HasMostRead
{
    /**
     * Get the most-read posts based on the number of views.
     */
    public function hasMostReadPosts(int $limit = 5): Collection
    {
        return Post::query()
            ->published()
            ->take($limit)
            ->get();
    }
}
