<?php

namespace Atannex\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Provides functionality to fetch hierarchical models and attach their posts efficiently.
 */
trait WithHierarchicalPosts
{
    /**
     * Retrieve hierarchical models by ID and attach their merged, sorted posts.
     *
     * The returned models have their specified posts relation replaced with a collection that
     * combines the model's own posts and posts from its leaf descendants, sorted by the
     * configured field and limited to the configured counts.
     *
     * @param string $modelClass Fully qualified Eloquent model class name.
     * @param array<string,mixed> $config Configuration options:
     *                                  - 'posts_with_id': array<int> IDs of root models to fetch.
     *                                  - 'limit': int Maximum number of root models to return.
     *                                  - 'relation_limit': int Maximum number of posts attached per model.
     *                                  - 'leaf_relation_limit': int Maximum number of posts taken from each leaf descendant.
     *                                  - 'sort': string Post field to sort by.
     *                                  - 'order': string 'asc' or 'desc' sort direction.
     * @param string $childrenRelation Name of the children relation on the model.
     * @param string $postsRelation Name of the posts relation on the model.
     * @return \Illuminate\Support\Collection<int,\Illuminate\Database\Eloquent\Model> Collection of models with the posts relation updated to the merged, sorted, and limited posts collection.
     */
    public function getHierarchicalWithPosts(
        string $modelClass,
        array $config,
        string $childrenRelation = 'children',
        string $postsRelation = 'posts'
    ): Collection {
        $ids = normalizeIds($config['posts_with_id']);
        $limit = (int) $config['limit'];
        $postLimit = (int) $config['relation_limit'];
        $leafPostLimit = (int) $config['leaf_relation_limit'];
        $sortField = $config['sort'];
        $order = strtolower($config['order']);

        $models = $this->fetchHierarchicalModels(
            $modelClass,
            $ids,
            $limit,
            $childrenRelation,
            $postsRelation,
            $sortField,
            $order,
            $leafPostLimit,
            $postLimit,
        );

        return $models->map(
            fn(Model $model) => $this->attachPostsToModel(
                $model,
                $postLimit,
                $leafPostLimit,
                $childrenRelation,
                $postsRelation,
                $sortField,
                $order,
            )
        );
    }

    /**
     * Retrieve hierarchical models by ID with their children and related posts preloaded and constrained.
     *
     * @param string $modelClass Fully-qualified model class name to query.
     * @param array<int|string> $ids List of model IDs to fetch.
     * @param int $limit Maximum number of root models to return.
     * @param string $childrenRelation Name of the children relation on the model.
     * @param string $postsRelation Name of the posts relation on the model.
     * @param string $sortField Field name to sort models and posts by.
     * @param string $order Sort direction, either 'asc' or 'desc'.
     * @param int $leafPostLimit Maximum number of posts to load per leaf descendant.
     * @param int $postLimit Maximum number of posts to load for the root model.
     * @return \Illuminate\Support\Collection Collection of models with the specified relations preloaded.
     */
    protected function fetchHierarchicalModels(
        string $modelClass,
        array $ids,
        int $limit,
        string $childrenRelation,
        string $postsRelation,
        string $sortField,
        string $order,
        int $leafPostLimit,
        int $postLimit
    ): Collection {
        return $modelClass::query()
            ->whereIn('id', $ids)
            ->with([
                sprintf('%s:id,parent_id,name,%s', $childrenRelation, $sortField),

                sprintf('%s.%s', $childrenRelation, $postsRelation) => fn($q) => $q
                    ->published()
                    ->orderBy($sortField, $order)
                    ->limit($leafPostLimit),

                $postsRelation => fn($q) => $q
                    ->published()
                    ->orderBy($sortField, $order)
                    ->limit($postLimit),
            ])
            ->orderBy($sortField, $order)
            ->limit($limit)
            ->get();
    }


    /**
     * Combine a model's own posts with those from its leaf descendants.
     */
    protected function attachPostsToModel(
        Model $model,
        int $postLimit,
        int $leafPostLimit,
        string $childrenRelation,
        string $postsRelation,
        string $sortField,
        string $order
    ): Model {
        $combinedPosts = $this->mergeModelAndLeafPosts(
            $model,
            $childrenRelation,
            $postsRelation,
            $leafPostLimit
        );

        $sortedPosts = $this->sortAndLimitPosts($combinedPosts, $sortField, $order, $postLimit);

        $model->setRelation($postsRelation, $sortedPosts);

        return $model;
    }

    /**
     * Merge a model’s own posts with posts from its leaf nodes.
     */
    protected function mergeModelAndLeafPosts(
        Model $model,
        string $childrenRelation,
        string $postsRelation,
        int $leafPostLimit
    ): Collection {
        $ownPosts = $model->$postsRelation;
        $leafPosts = $this->collectLeafPostsIterative($model, $childrenRelation, $postsRelation, $leafPostLimit);

        return $ownPosts->merge($leafPosts)->unique('id')->values();
    }

    /**
     * Sort and limit posts according to the given configuration.
     */
    protected function sortAndLimitPosts(
        Collection $posts,
        string $sortField,
        string $order,
        int $limit
    ): Collection {
        return $posts
            ->sortBy([$sortField => $order === 'desc' ? SORT_DESC : SORT_ASC])
            ->take($limit)
            ->values();
    }

    /**
     * Collect posts from all leaf nodes iteratively for performance.
     */
    protected function collectLeafPostsIterative(
        Model $model,
        string $childrenRelation,
        string $postsRelation,
        int $leafPostLimit
    ): Collection {
        $stack = [$model];
        $leafPosts = collect();

        while ($stack) {
            $current = array_pop($stack);

            if ($current->$childrenRelation->isEmpty()) {
                $leafPosts = $leafPosts->merge(
                    $current->$postsRelation->take($leafPostLimit)
                );

                continue;
            }

            foreach ($current->$childrenRelation as $child) {
                $stack[] = $child;
            }
        }

        return $leafPosts;
    }
}