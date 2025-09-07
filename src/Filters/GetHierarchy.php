<?php

namespace Atannex\Filters;

use Illuminate\Support\Collection;


trait GetHierarchy
{
    /**
     * Get all ancestors of this category (up to root)
     *
     * @return Collection
     */
    public function getAncestors(): Collection
    {
        $ancestors = collect();
        $parent = $this->parent;

        while ($parent) {
            $ancestors->push($parent);
            $parent = $parent->parent;
        }

        return $ancestors;
    }

    /**
     * Get all descendants of this category (recursive)
     *
     * @return Collection
     */
    public function getDescendants(): Collection
    {
        $descendants = collect();

        foreach ($this->children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->getDescendants());
        }

        return $descendants;
    }
}
