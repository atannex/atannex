<?php

namespace Atannex\Traits;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

trait HasPostsForHierarchy
{
    /**
     * Fetch posts for a hierarchy of items, optimized for performance.
     *
     * @param array $ids Array of item IDs
     * @param array $options Configuration options (limit, limit_per_leaf_post, sort_by, sort_dir)
     * @param string $modelClass Model class name
     * @param string $relationName Name of the relation to query
     * @param string|null $foreignKey Optional foreign key for direct column queries
     * @param callable|null $leafIdResolver Optional callback to resolve leaf IDs
     * @param callable|null $groupByResolver Optional callback to group posts
     * @return Collection Collection of unique posts
     */
    protected function fetchPostsForHierarchy(
        array $ids,
        array $options,
        string $modelClass,
        string $relationName,
        ?string $foreignKey = null,
        ?callable $leafIdResolver = null,
        ?callable $groupByResolver = null
    ): Collection {

        // Normalize and validate options
        $limit = max(1, (int) ($options['limit'] ?? 10));
        $limitPerLeafPost = max(1, (int) ($options['limit_per_leaf_post'] ?? 1));
        $sortBy = $options['sort'] ?? 'published_at';
        $sortDir = $options['order'] ?? 'desc';

        // Fetch items with children in a single query
        $items = $modelClass::with('children')->whereIn('id', $ids)->get();
        $allPosts = collect();

        foreach ($items as $item) {
            // Determine leaf IDs based on whether the item has children
            $leafIds = $this->resolveLeafIds($item, $leafIdResolver);

            // Build the post query
            $query = Post::published()
                ->when($foreignKey, fn($q) => $q->whereIn($foreignKey, $leafIds))
                ->when(!$foreignKey, fn($q) => $q->whereHas(
                    $relationName,
                    fn(Builder $builder) => $builder->whereIn("{$relationName}.id", $leafIds)
                ))
                ->orderBy($sortBy, $sortDir);

            // Apply limit only for leaf nodes without children
            $posts = $item->children->isEmpty()
                ? $query->take($limit)->get()
                : $query->get()->groupBy($groupByResolver ?? fn(Post $p) => $this->resolveGroupByKey($p, $foreignKey, $relationName))
                ->flatMap(fn($group) => $group->take($limitPerLeafPost));

            $allPosts = $allPosts->merge($posts);
        }

        // Return unique posts sorted according to specified criteria
        return $allPosts->unique('id')
            ->sortBy($sortBy, SORT_REGULAR, $sortDir === 'desc')
            ->values();
    }

    /**
     * Resolve leaf IDs for an item.
     *
     * @param mixed $item The item to process
     * @param callable|null $leafIdResolver Optional callback to resolve leaf IDs
     * @return array Array of leaf IDs
     */
    private function resolveLeafIds($item, ?callable $leafIdResolver): array
    {
        if ($item->children->isEmpty()) {
            return [$item->id];
        }

        return $leafIdResolver
            ? $leafIdResolver($item)
            : $item->getDescendants()
            ->filter(fn($child) => $child->children->isEmpty())
            ->pluck('id')
            ->all();
    }

    /**
     * Resolve the grouping key for a post.
     *
     * @param Post $post The post to group
     * @param string|null $foreignKey Optional foreign key
     * @param string $relationName Relation name
     * @return mixed Grouping key
     */
    private function resolveGroupByKey(Post $post, ?string $foreignKey, string $relationName)
    {
        return $foreignKey ? $post->{$foreignKey} : $post->{$relationName}->first()->id;
    }
}
