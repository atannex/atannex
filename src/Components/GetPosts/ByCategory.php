<?php

namespace Atannex\Components\GetPosts;

use App\Models\Regions\Category;
use Illuminate\Support\Collection;
use Atannex\Traits\HasPostsForHierarchy;

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
