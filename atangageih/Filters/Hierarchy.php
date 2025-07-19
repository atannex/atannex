<?php

namespace Atangageih\Filters;

use App\Enums\Flag;
use App\Models\Tags\Tag;
use Illuminate\Support\Collection;

/**
 * Trait Hierarchy
 *
 * Provides methods for handling hierarchical relationships and slug-based resolution.
 *
 * Note: Removed logic that merges/inherits sections from parent/root categories
 * to child categories to enforce strict category-specific section associations.
 */
trait Hierarchy
{

    /**
     * Resolve a tag from a slug path.
     *
     * @param string $slugPath
     * @return ?Tag
     */
    protected function resolveTagFromSlugs(string $slugPath): ?Tag
    {
        $slug = last($this->extractSlugs($slugPath));

        $tag = Tag::with([
            'posts.category' => fn($q) => $q->where('flag', Flag::PUBLISHED),
            'posts.category.sections' => fn($q) => $q->where('sections.flag', Flag::PUBLISHED),
        ])->where('slug', $slug)->first();

        if (!$tag) {
            return null;
        }

        $firstPost = $tag->posts->filter(fn($post) => $post->category)->first();

        if (!$firstPost) {
            return $tag;
        }

        $tag->setRelation('sections', $firstPost->category->sections ?? collect());

        return $tag;
    }

    /**
     * Get descendants and self for the current model.
     *
     * @return Collection
     */
    public function getDescendantsAndSelf(): Collection
    {
        $descendants = collect([$this]);
        foreach ($this->children as $child) {
            $descendants = $descendants->merge($child->getDescendantsAndSelf());
        }

        return $descendants->unique('id');
    }

    /**
     * Get ancestors of the current model.
     *
     * @return Collection
     */
    public function getAncestors(): Collection
    {
        $ancestors = collect();
        $current = $this->parent;

        while ($current) {
            $ancestors->push($current);
            $current = $current->parent;
        }

        return $ancestors->reverse()->values();
    }
}
