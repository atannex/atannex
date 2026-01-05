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
     * Get the top-most ancestor (root) of a tree node.
     *
     * @param Model $node The node whose root should be resolved.
     * @return Model The root node of the given node's tree (the last ancestor, or the node itself if it has no ancestors).
     */
    protected function getRoot(Model $node): Model
    {
        $ancestors = $node->getAncestors();
        return $ancestors->isNotEmpty() ? $ancestors->last() : $node;
    }

    /**
     * Collect IDs of the given node and all of its descendants, optionally excluding a specific ID.
     *
     * @param Model $node The node whose subtree IDs will be collected.
     * @param int|null $excludeId Optional ID to exclude from the returned collection.
     * @return Collection<int> A collection of unique node IDs including the given node (exclusion applied if provided).
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
     * Retrieve leaf nodes (nodes that have no children) within the given node's tree.
     *
     * When a content relation is provided, each returned node includes a `total_count`
     * attribute representing the number of related content items; if a filter scope
     * name is provided and exists on the related model, that scope is applied to the count.
     *
     * @param Model $node The node whose tree will be searched for leaf nodes.
     * @param string|null $contentRelation Relation name on the node used to count related content (e.g. "posts"). When omitted, no counts are added.
     * @param string|null $filterScope Local scope name on the related content model to apply to the count (e.g. "published").
     * @param int|null $excludeId Optional node ID to exclude from the result set.
     * @param int $limit Maximum number of leaf nodes to return (clamped by sanitizeLimit).
     * @param string $direction Sort direction for `total_count`; either 'desc' or 'asc'.
     * @return \Illuminate\Support\Collection Collection of leaf node models; nodes include a `total_count` attribute when `$contentRelation` is provided.
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
     * Combine a node's own related content with content collected from its leaf descendants.
     *
     * Returns a collection of content items de-duplicated by `id` and reindexed.
     *
     * @param Model $node The root node whose content and descendant leaf content will be merged.
     * @param string $childrenRelation The name of the relation on the node that returns its child nodes.
     * @param string $contentRelation The name of the relation on nodes that returns their content items.
     * @param int $leafLimit Maximum number of items to take from each leaf node when collecting leaf content.
     * @return \Illuminate\Support\Collection A collection of unique content items merged from the node and its leaf descendants.
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
     * Collect content items from every leaf node under the given node.
     *
     * Traverses the node's subtree and aggregates content from nodes that have no children.
     *
     * @param \Illuminate\Database\Eloquent\Model $node Root node whose subtree will be searched.
     * @param string $childrenRelation Name of the relation that returns a node's children.
     * @param string $contentRelation Name of the relation that returns a node's content items.
     * @param int $leafLimit Maximum number of content items to take from each leaf node.
     * @return \Illuminate\Support\Collection Collection of content models aggregated from leaf nodes (up to $leafLimit per leaf).
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
     * Attach merged content from the node and its leaf descendants to the node and order/limit the result.
     *
     * Merges the node's own related content with content collected from leaf nodes, sorts by the given
     * field and order, truncates to $totalLimit, and sets the resulting collection as the $contentRelation
     * relation on the provided node.
     *
     * @param Model  $node             The node to attach content to.
     * @param string $childrenRelation Name of the children relationship on the node.
     * @param string $contentRelation  Name of the content relationship to collect and attach.
     * @param int    $leafLimit        Maximum number of items to include from each leaf node when merging.
     * @param int    $totalLimit       Maximum number of items to attach to the node after sorting.
     * @param string $sortField        Field name used to sort merged content (default: 'published_at').
     * @param string $order            Sort direction, either 'desc' or 'asc' (default: 'desc').
     * @return Model The original node with the $contentRelation relation set to the merged, sorted collection.
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
    /**
     * Paginate the given query using a normalized per-page limit.
     *
     * The provided `$limit` is clamped to the allowed range before pagination.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query The query builder to paginate.
     * @param int $limit Desired items per page; will be normalized to the allowed range.
     * @return \Illuminate\Pagination\LengthAwarePaginator A paginator for the query results using the sanitized limit.
     */

    protected function paginate(Builder $query, int $limit): LengthAwarePaginator
    {
        return $query->paginate($this->sanitizeLimit($limit));
    }

    /**
     * Clamp a limit value to the range 1 through 100.
     *
     * @param int $limit The requested limit value.
     * @return int An integer between 1 and 100. 
     */
    protected function sanitizeLimit(int $limit): int
    {
        return max(1, min($limit, 100));
    }

    /**
     * Standard content relations for eager loading.
     *
     * @return string[] Relation paths to eager-load for content (e.g. 'category.parent', 'regions', 'tags', 'author.user').
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