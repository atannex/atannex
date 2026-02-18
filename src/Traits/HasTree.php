<?php

declare(strict_types=1);

namespace Atannex\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait HasTree
{
    /**
     * Get the root node of a tree.
     */
    protected function getRoot(Model $node): Model
    {
        return $node->getAncestors()->last() ?? $node;
    }

    /**
     * Get IDs of the node and all its descendants.
     */
    protected function getTreeIds(Model $node, ?int $excludeId = null): Collection
    {
        $ids = $node->getDescendants()
            ->pluck($node->getKeyName())
            ->push($node->getKey());

        if ($excludeId !== null) {
            $ids = $ids->reject(fn ($id) => $id === $excludeId);
        }

        return $ids->unique()->values();
    }

    /**
     * Fetch leaf nodes within a tree.
     */
    protected function getLeafNodes(Model $node, ?string $contentRelation = null, ?string $filterScope = null, ?int $excludeId = null, int $limit = 12, string $direction = 'desc'): Collection
    {
        $query = $node->newQuery()
            ->whereIn(
                $node->getKeyName(),
                $this->getTreeIds($node, $excludeId)
            )
            ->whereDoesntHave('children');

        if ($contentRelation && $filterScope) {
            $query->withCount([
                "{$contentRelation} as total_count" => fn (Builder $q) => $q->{$filterScope}(),
            ])->orderBy('total_count', $direction);
        }

        return $query->limit($limit)->get();
    }

    /**
     * Collect content from leaf nodes (iterative DFS).
     */
    protected function collectLeafContent(Model $node, string $childrenRelation, string $contentRelation, int $leafLimit): Collection
    {
        $stack = [$node];
        $content = collect();

        while (! empty($stack)) {
            $current = array_pop($stack);

            if ($current->$childrenRelation->isEmpty()) {
                $content->push(
                    ...$current->$contentRelation->take($leafLimit)
                );

                continue;
            }

            foreach ($current->$childrenRelation as $child) {
                $stack[] = $child;
            }
        }

        return $content;
    }

    /**
     * Attach merged node + leaf content to a node.
     */
    protected function attachContentToNode(Model $node, string $childrenRelation, string $contentRelation, int $leafLimit, int $totalLimit, string $sortField = 'published_at', string $order = 'desc'): Model
    {
        $content = $node->$contentRelation
            ->merge(
                $this->collectLeafContent(
                    $node,
                    $childrenRelation,
                    $contentRelation,
                    $leafLimit
                )
            )
            ->unique('id')
            ->sortBy(
                $sortField,
                SORT_REGULAR,
                $order === 'desc'
            )
            ->take($totalLimit)
            ->values();

        $node->setRelation($contentRelation, $content);

        return $node;
    }
}
