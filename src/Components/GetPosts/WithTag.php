<?php

namespace Atannex\Components\GetPosts;

use App\Models\Tags\Tag;
use Illuminate\Support\Collection;

trait WithTag
{
    // use HasHierarchyWithRelations;

    /**
     * Get tags with posts.
     *
     * @param array $config
     * [
     *   'ids' => [1,2,3],              // tag IDs
     *   'limit' => 4,                   // max number of tags
     *   'relation_limit' => 3,          // posts per tag
     *   'leaf_relation_limit' => 1,     // posts per leaf tag
     *   'order_column' => 'published_at'
     * ]
     *
     * @return Collection
     */
    public function getTagWithPosts(array $config): Collection
    {
        $config = array_merge($config, [
            'model' => Tag::class,
            'relation' => 'posts',
            'children' => 'children'
        ]);

        return $this->getModelsWithRelation($config);
    }
}
