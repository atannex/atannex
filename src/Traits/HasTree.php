<?php

declare(strict_types=1);

namespace Atannex\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Universal Tree Utilities for hierarchical models with posts (or any content type).
 *
 * Requirements for models using this trait:
 * - Implement: getAncestors()
 * - Implement: getDescendants()
 * - Define: children() relation
 * - Related content must have a "published()" local scope
 */
trait HasTree
{
    // ==========================================================================
    // 1️⃣ GENERIC TREE OPERATIONS
    // ==========================================================================

    /**
     * Resolve the root (top ancestor) of a tree-based model instance.
     */
    protected function getRoot(Model $node): Model
    {
        $ancestors = $node->getAncestors();
        return $ancestors->isNotEmpty() ? $ancestors->last() : $node;
    }

    /**
     * Get IDs of a node + all descendants.
     * Optional: exclude a specific node ID.
     */
    protected function getTreeIds(Model $node, ?int $excludeId = null): Collection
    {
        $ids = $node->getDescendants()
            ->pluck($node->getKeyName())
            ->push($node->getKey())
            ->unique();

        return $excludeId
            ? $ids->reject(fn($id) => $id === $excludeId)->values()
            : $ids->values();
    }

    // ==========================================================================
    // 2️⃣ LEAF NODE RETRIEVAL WITH POST COUNTS
    // ==========================================================================

    /**
     * Get leaf nodes (no children), optionally with content counts.
     *
     * @param  string|null  $contentRelation  e.g. "posts"
     * @param  string|null  $filterScope      local scope on related content (e.g. "published")
     * @param  int|null     $excludeId        optional node ID to exclude
     * @param  int          $limit            max number of leaves
     * @param  string       $direction        'desc' or 'asc' for sorting counts
     */
    protected function getLeafNodes(
        Model $node,
        ?string $contentRelation = null,
        ?string $filterScope = null,
        ?int $excludeId = null,
        int $limit = 12,
        string $direction = 'desc'
    ): Collection {
        $query = $node->newQuery()
            ->whereIn($node->getKeyName(), $this->getTreeIds($node, $excludeId))
            ->whereDoesntHave('children')
            ->limit($this->sanitizeLimit($limit));

        return $query->when($contentRelation, function (Builder $q) use ($contentRelation, $filterScope, $direction) {
            $q->withCount([
                $contentRelation . ' as total_count' => function (Builder $r) use ($filterScope) {
                    if ($filterScope && method_exists($r->getModel(), $filterScope)) {
                        $r->{$filterScope}();
                    }
                }
            ])->orderBy('total_count', $direction);
        })->get();
    }

    // ==========================================================================
    // 3️⃣ CONTENT ATTACHMENT & MERGING
    // ==========================================================================

    /**
     * Merge a node's own content with leaf content, optionally filtered and limited.
     *
     * @param  Model       $node
     * @param  string      $childrenRelation
     * @param  string      $contentRelation
     * @param  int         $leafLimit
     */
    protected function mergeNodeAndLeafContent(
        Model $node,
        string $childrenRelation,
        string $contentRelation,
        int $leafLimit
    ): Collection {
        $ownContent = $node->$contentRelation;

        $leafContent = $this->collectLeafContentIterative($node, $childrenRelation, $contentRelation, $leafLimit);

        return $ownContent->merge($leafContent)->unique('id')->values();
    }

    /**
     * Iteratively collect content from all leaf nodes (avoids deep recursion).
     */
    protected function collectLeafContentIterative(
        Model $node,
        string $childrenRelation,
        string $contentRelation,
        int $leafLimit
    ): Collection {
        $stack = [$node];
        $leafContent = collect();

        while ($stack) {
            $current = array_pop($stack);

            if ($current->$childrenRelation->isEmpty()) {
                $leafContent = $leafContent->merge(
                    $current->$contentRelation->take($leafLimit)
                );
                continue;
            }

            foreach ($current->$childrenRelation as $child) {
                $stack[] = $child;
            }
        }

        return $leafContent;
    }

    /**
     * Attach merged and sorted content to a node.
     *
     * @param  Model  $node
     * @param  string $childrenRelation
     * @param  string $contentRelation
     * @param  int    $leafLimit
     * @param  int    $totalLimit
     * @param  string $sortField
     * @param  string $order
     */
    protected function attachContentToNode(
        Model $node,
        string $childrenRelation,
        string $contentRelation,
        int $leafLimit,
        int $totalLimit,
        string $sortField = 'published_at',
        string $order = 'desc'
    ): Model {
        $merged = $this->mergeNodeAndLeafContent($node, $childrenRelation, $contentRelation, $leafLimit);

        $sorted = $merged->sortBy([$sortField => $order === 'desc' ? SORT_DESC : SORT_ASC])
            ->take($totalLimit)
            ->values();

        $node->setRelation($contentRelation, $sorted);

        return $node;
    }

    // ==========================================================================
    // 4️⃣ PAGINATION & QUERY UTILITIES
    // ==========================================================================

    protected function paginate(Builder $query, int $limit): LengthAwarePaginator
    {
        return $query->paginate($this->sanitizeLimit($limit));
    }

    protected function sanitizeLimit(int $limit): int
    {
        return max(1, min($limit, 100));
    }

    /**
     * Standard content relations for eager-loading.
     */
    protected function contentRelations(): array
    {
        return [
            'category.parent',
            'regions',
            'tags',
            'author.user',
        ];
    }
}
