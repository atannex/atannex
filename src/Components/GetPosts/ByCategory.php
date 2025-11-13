<?php

namespace Atannex\Components\GetPosts;

use App\Models\Regions\Category;
use Atannex\Traits\HasPostsForHierarchy;
use Illuminate\Support\Collection;

trait ByCategory
{
    use HasPostsForHierarchy;

    public function getPostsForCategory(array $config = []): Collection
    {
        $categoryIds = (array) ($config['category_id']);

        return $this->fetchPostsForHierarchy(
            $categoryIds,
            $config,
            Category::class,
            'category',
            'category_id'
        );
    }
}
