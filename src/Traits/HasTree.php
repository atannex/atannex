<?php

namespace Atannex\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Universal Tree Utilities
 *
 * Requirements for tree models using this trait:
 * - must implement: getAncestors()
 * - must implement: getDescendants()
 * - must define:    children() relation
 */
trait HasTree
{
    // ==========================================================================
    // 1️⃣ GENERIC TREE OPERATIONS (NO MODEL COUPLING)
    // ==========================================================================

    /**
     * Resolve the root (top ancestor) of a tree-based model instance.
     */
    protected function getRoot(Model $node): Model
    {
        $ancestors = $node->getAncestors();
        return $ancestors->isNotEmpty()
            ? $ancestors->last()
            : $node;
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

    /**
     * Get leaf nodes (no children), optionally sorted by related count.
     *
     * @param string|null $countRelation  e.g. "posts"
     * @param string|null $filterScope    local scope name on relation
     */
    protected function getLeafNodes(
        Model $node,
        ?string $countRelation = null,
        ?string $filterScope = null,
        ?int $excludeId = null,
        int $limit = 12
    ): Collection {
        $query = $node->newQuery()
            ->whereIn($node->getKeyName(), $this->getTreeIds($node, $excludeId))
            ->whereDoesntHave('children')
            ->limit($this->sanitizeLimit($limit));

        if ($countRelation) {
            $query->withCount([
                $countRelation . ' as total_count' =>
                function ($q) use ($filterScope) {
                    if ($filterScope && method_exists($q->getModel(), $filterScope)) {
                        $q->{$filterScope}();
                    }
                }
            ])->orderByDesc('total_count');
        }

        return $query->get();
    }

    // ==========================================================================
    // 2️⃣ GENERIC QUERY UTILITIES (OPTIONAL & EXTENSIBLE)
    // ==========================================================================

    /**
     * Paginate a safe limit (prevents excessive loads).
     */
    protected function paginate(Builder $query, int $limit): LengthAwarePaginator
    {
        return $query->paginate($this->sanitizeLimit($limit));
    }

    // ==========================================================================
    // 3️⃣ INTERNAL HELPERS
    // ==========================================================================

    protected function sanitizeLimit(int $limit): int
    {
        return max(1, min($limit, 100));
    }

    /**
     * Standard eager loads for consistent performance.
     */
    protected function postRelations(): array
    {
        return [
            'category.parent',
            'regions',
            'tags',
            'author.user',
        ];
    }
}
