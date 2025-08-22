<?php

namespace Atannex\Filters;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Trait for hierarchical traversal of Eloquent models.
 */
trait GetHierarchy
{
    /**
     * Traverses the model hierarchy iteratively using DFS or BFS.
     *
     * @param callable(Model): iterable<Model> $getNext Function returning related models to traverse.
     * @param bool $includeSelf Whether to include the current model in the result.
     * @param bool $reverse Whether to reverse the order of the final collection.
     * @param string $strategy Traversal strategy: 'dfs' (depth-first) or 'bfs' (breadth-first).
     * @return Collection<int, Model>
     */
    protected function traverseHierarchy(
        callable $getNext,
        bool $includeSelf = true,
        bool $reverse = false,
        string $strategy = 'dfs'
    ): Collection {
        $results = new Collection();
        $visited = [];

        $queue = [];

        if ($includeSelf) {
            $queue[] = $this;
        } else {
            $queue = [...$getNext($this)];
        }

        while (!empty($queue)) {
            /** @var Model $current */
            $current = ($strategy === 'bfs')
                ? array_shift($queue)  // queue-like for BFS
                : array_pop($queue);   // stack-like for DFS

            if (!$current || isset($visited[$current->id])) {
                continue;
            }

            $visited[$current->id] = true;
            $results->push($current);

            foreach ($getNext($current) as $related) {
                $queue[] = $related;
            }
        }

        return $reverse
            ? $results->reverse()->values()
            : $results->values();
    }

    /**
     * Retrieves all descendants of the current model, including itself.
     *
     * @param string $strategy 'dfs' or 'bfs'
     * @return Collection<int, Model>
     */
    public function getDescendantsAndSelf(string $strategy = 'dfs'): Collection
    {
        return $this->traverseHierarchy($this->childrenResolver(), strategy: $strategy);
    }

    /**
     * Retrieves all ancestors of the current model, excluding itself.
     *
     * @param string $strategy 'dfs' or 'bfs'
     * @return Collection<int, Model>
     */
    public function getAncestors(string $strategy = 'dfs'): Collection
    {
        return $this->traverseHierarchy(
            $this->parentResolver(),
            includeSelf: false,
            reverse: true,
            strategy: $strategy
        );
    }

    /* -----------------------------------------------------------------
     |  Private relation resolvers
     | -----------------------------------------------------------------
     */

    /**
     * Resolver for children traversal.
     *
     * @return \Closure(Model): iterable<Model>
     */
    private function childrenResolver(): \Closure
    {
        return fn(Model $model): array =>
        $model->relationLoaded('children')
            ? $model->children->all()
            : ($model->children ? $model->children->all() : []);
    }

    /**
     * Resolver for parent traversal.
     *
     * @return \Closure(Model): iterable<Model>
     */
    private function parentResolver(): \Closure
    {
        return fn(Model $model): array =>
        $model->relationLoaded('parent') && $model->parent
            ? [$model->parent]
            : ($model->parent ? [$model->parent] : []);
    }
}
