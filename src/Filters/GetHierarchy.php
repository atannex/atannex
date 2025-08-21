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
     * Traverses the model hierarchy using a provided relation accessor.
     *
     * @param callable $getNext A function that returns related models to traverse (e.g., children or parent).
     * @param bool $includeSelf Whether to include the current model in the result.
     * @param bool $reverse Whether to reverse the order of the final collection.
     * @return Collection<int, Model>
     */
    protected function traverseHierarchy(callable $getNext, bool $includeSelf = true, bool $reverse = false): Collection
    {
        $results = new Collection();

        if ($includeSelf) {
            $results->push($this);
        }

        foreach ($getNext($this) as $related) {
            $results = $results->merge($related->traverseHierarchy($getNext, true, false));
        }

        return $reverse ? $results->reverse()->values() : $results->unique('id')->values();
    }

    /**
     * Retrieves all descendants of the current model, including itself.
     *
     * @return Collection<int, Model>
     */
    public function getDescendantsAndSelf(): Collection
    {
        return $this->traverseHierarchy(fn(Model $model): array => $model->children ? $model->children->all() : []);
    }

    /**
     * Retrieves all ancestors of the current model, excluding itself.
     *
     * @return Collection<int, Model>
     */
    public function getAncestors(): Collection
    {
        return $this->traverseHierarchy(
            fn(Model $model): array => $model->parent ? [$model->parent] : [],
            includeSelf: false,
            reverse: true
        );
    }
}
