<?php

namespace Atannex\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Atannex\Views\Traits\CanNormalize;

trait WithHierarchicalPosts
{
    use CanNormalize;

    /**
     * Retrieve hierarchical models along with their latest posts.
     *
     * Fetches a collection of models with nested children and associated posts.
     * Supports sorting, limiting, and merging posts from both parent and leaf child models.
     *
     * @param string $modelClass The fully qualified class name of the Eloquent model
     * @param array $config Configuration array containing:
     *                      - 'posts_with_id': array of model IDs to include
     *                      - 'limit': maximum number of parent models to retrieve
     *                      - 'relation_limit': maximum number of posts per model
     *                      - 'leaf_relation_limit': maximum number of posts from leaf children
     *                      - 'sort': column to sort posts/models by
     *                      - 'order': sort direction ('asc' or 'desc')
     * @param string $childrenRelation The name of the relationship for child models
     * @param string $postsRelation The name of the relationship for posts
     * @return Collection A collection of models with posts attached
     */
    public function getHierarchicalWithPosts(
        string $modelClass,
        array $config,
        string $childrenRelation = 'children',
        string $postsRelation = 'posts'
    ): Collection {
        $modelIds = $this->normalizeIds($config['posts_with_id']);
        $limit = $config['limit'];
        $postLimit = $config['relation_limit'];
        $leafPostLimit = $config['leaf_relation_limit'];
        $sortField = $config['sort'];
        $order = strtolower($config['order']);

        $models = $modelClass::query()
            ->whereIn('id', $modelIds)
            ->with([
                "{$childrenRelation}:id,parent_id,name,{$sortField}",
                "{$childrenRelation}.{$postsRelation}" => fn($q) => $q->orderBy($sortField, $order)->limit($leafPostLimit),
                $postsRelation => fn($q) => $q->orderBy($sortField, $order)->limit($postLimit),
            ])
            ->orderBy($sortField, $order)
            ->limit($limit)
            ->get();

        return $models->map(
            fn(Model $model) =>
            $this->attachPostsToModel($model, $postLimit, $leafPostLimit, $childrenRelation, $postsRelation, $sortField, $order)
        );
    }

    /**
     * Attach posts to a model, combining its own posts with posts from leaf children.
     *
     * @param Model $model The model to attach posts to
     * @param int $postLimit Maximum number of posts to attach
     * @param int $leafPostLimit Maximum posts per leaf child
     * @param string $childrenRelation Name of the children relationship
     * @param string $postsRelation Name of the posts relationship
     * @param string $sortField Column to sort by
     * @param string $order Sort direction ('asc' or 'desc')
     * @return Model The model with posts attached
     */
    private function attachPostsToModel(
        Model $model,
        int $postLimit,
        int $leafPostLimit,
        string $childrenRelation,
        string $postsRelation,
        string $sortField,
        string $order
    ): Model {
        $allPosts = $model->$postsRelation;

        $leafPosts = $this->collectLeafPostsIterative($model, $childrenRelation, $postsRelation, $leafPostLimit);

        if ($leafPosts->isNotEmpty()) {
            $allPosts = $allPosts->merge($leafPosts);
        }

        $allPosts = $allPosts
            ->unique('id')
            ->sortBy([$sortField => $order === 'desc' ? SORT_DESC : SORT_ASC])
            ->take($postLimit)
            ->values();

        $model->setRelation($postsRelation, $allPosts);

        return $model;
    }

    /**
     * Collect posts from all leaf child models using an iterative approach.
     *
     * @param Model $model The parent model
     * @param string $childrenRelation Children relationship name
     * @param string $postsRelation Posts relationship name
     * @param int $leafPostLimit Maximum posts per leaf model
     * @return Collection Collection of posts from leaf models
     */
    private function collectLeafPostsIterative(
        Model $model,
        string $childrenRelation,
        string $postsRelation,
        int $leafPostLimit
    ): Collection {
        $stack = [$model];
        $leafPosts = collect();

        while (!empty($stack)) {
            $current = array_pop($stack);

            if ($current->$childrenRelation->isEmpty()) {
                $leafPosts = $leafPosts->merge($current->$postsRelation->take($leafPostLimit));
            } else {
                foreach ($current->$childrenRelation as $child) {
                    $stack[] = $child;
                }
            }
        }

        return $leafPosts;
    }
}
