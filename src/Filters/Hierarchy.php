<?php

declare(strict_types=1);

namespace Atannex\Filters;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

trait Hierarchy
{
    /**
     * Override if your column names differ.
     */
    protected string $parentColumn = 'parent_id';

    protected string $keyColumn = 'id';

    /**
     * Cache all nodes in memory for current execution.
     */
    protected static ?Collection $hierarchyCache = null;

    /**
     * Get all nodes indexed by ID.
     */
    protected function getAllNodes(): Collection
    {
        if (static::$hierarchyCache === null) {
            static::$hierarchyCache = static::query()
                ->get()
                ->keyBy($this->getKeyName());
        }

        return static::$hierarchyCache;
    }

    /**
     * Get children grouped by parent_id.
     */
    protected function getChildrenMap(): Collection
    {
        return $this->getAllNodes()->groupBy($this->parentColumn);
    }

    /**
     * Get all ancestors (safe, no cycles).
     */
    public function getAncestors(): EloquentCollection
    {
        $ancestors = new EloquentCollection();
        $visited = [];

        $nodes = $this->getAllNodes();
        $currentId = $this->{$this->parentColumn};

        while ($currentId && isset($nodes[$currentId])) {
            if (isset($visited[$currentId])) {
                break;
            }

            $visited[$currentId] = true;

            $parent = $nodes[$currentId];
            $ancestors->push($parent);

            $currentId = $parent->{$this->parentColumn};
        }

        return $ancestors;
    }

    /**
     * Get all descendants (DFS traversal, optimized).
     */
    public function getDescendants(): EloquentCollection
    {
        $descendants = new EloquentCollection();
        $visited = [];

        $childrenMap = $this->getChildrenMap();
        $stack = [$this->{$this->keyColumn}];

        while (!empty($stack)) {
            $currentId = array_pop($stack);

            if (isset($visited[$currentId])) {
                continue;
            }

            $visited[$currentId] = true;

            foreach ($childrenMap[$currentId] ?? [] as $child) {
                $descendants->push($child);
                $stack[] = $child->{$this->keyColumn};
            }
        }

        return $descendants;
    }

    /**
     * Get ancestor IDs including self.
     */
    public function getSelfAndAncestorIds(): array
    {
        return $this->getAncestors()
            ->pluck($this->keyColumn)
            ->prepend($this->{$this->keyColumn})
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Get descendant IDs including self.
     */
    public function getSelfAndDescendantIds(): array
    {
        return $this->getDescendants()
            ->pluck($this->keyColumn)
            ->prepend($this->{$this->keyColumn})
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Determine if current node is root.
     */
    public function isRoot(): bool
    {
        return empty($this->{$this->parentColumn});
    }

    /**
     * Determine if node is leaf (no children).
     */
    public function isLeaf(): bool
    {
        $childrenMap = $this->getChildrenMap();

        return empty($childrenMap[$this->{$this->keyColumn}] ?? []);
    }

    /**
     * Get depth level (distance from root).
     */
    public function getDepth(): int
    {
        return $this->getAncestors()->count();
    }

    /**
     * Reset hierarchy cache manually (use after mutations).
     */
    public static function clearHierarchyCache(): void
    {
        static::$hierarchyCache = null;
    }
}
