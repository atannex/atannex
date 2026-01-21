<?php

namespace Atannex\Filters;

use Illuminate\Support\Collection;

trait Hierarchy
{
    /**
     * Get all ancestors without N+1 queries.
     */
    public function getAncestors(): Collection
    {
        $ancestors = collect();
        $currentId = $this->parent_id;

        $allNodes = self::query()->get()->keyBy('id');

        while ($currentId && isset($allNodes[$currentId])) {
            $parent = $allNodes[$currentId];
            $ancestors->push($parent);
            $currentId = $parent->parent_id;
        }

        return $ancestors;
    }

    /**
     * Get all descendants without N+1 queries.
     */
    public function getDescendants(): Collection
    {
        $descendants = collect();
        $stack = collect([$this->id]);

        $allNodes = self::query()->get()->keyBy('id');

        while ($stack->isNotEmpty()) {
            $currentId = $stack->shift();

            foreach ($allNodes as $node) {
                if ($node->parent_id === $currentId) {
                    $descendants->push($node);
                    $stack->push($node->id);
                }
            }
        }

        return $descendants;
    }

    /**
     * Get IDs of self + descendants
     */
    public function getSelfAndDescendantIds(): array
    {
        return collect([$this->id])
            ->merge($this->getDescendants()->pluck('id'))
            ->unique()
            ->values()
            ->toArray();
    }

    /**
     * Get IDs of self + ancestors
     */
    public function getSelfAndAncestorIds(): array
    {
        return collect([$this->id])
            ->merge($this->getAncestors()->pluck('id'))
            ->unique()
            ->values()
            ->toArray();
    }
}
