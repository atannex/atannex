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
     * Retrieve hierarchical models along with their latest posts.
     *
     * @param  string  $modelClass  Fully qualified class name of the Eloquent model.
     * @param  array<string,mixed>  $config  Configuration array:
     *                                       - 'posts_with_id': array<int>
     *                                       - 'limit': int
     *                                       - 'relation_limit': int
     *                                       - 'leaf_relation_limit': int
     *                                       - 'sort': string
     *                                       - 'order': string ('asc'|'desc')
     * @param  string  $childrenRelation  Child relation name.
     * @param  string  $postsRelation  Post relation name.
     * @return Collection<int, Model> Collection of models with merged posts.
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
     * Fetch models with their children and post relationships.
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
