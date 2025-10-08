<?php

declare(strict_types=1);

namespace Atannex\Traits;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

/**
 * Provides optimized fetching of posts across hierarchical models.
 */
trait HasPostsForHierarchy
{
    /**
     * Fetch posts across a hierarchy of models with strict performance priority.
     *
     * @param  array<int>          $ids
     * @param  array<string,mixed> $options
     * @param  class-string        $modelClass
     * @param  string              $relationName
     * @param  string|null         $foreignKey
     * @param  callable|null       $leafIdResolver
     * @param  callable|null       $groupByResolver
     * @return Collection<int, Post>
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
        $limit         = (int) $options['limit'];
        $limitPerLeaf  = (int) $options['leaf_relation_limit'];
        $sortBy        = $options['sort'];
        $sortDir       = $options['order'];

        $items = $this->fetchHierarchyItems($modelClass, $ids);

        $allPosts = $items->reduce(function (Collection $carry, $item) use (
            $limit,
            $limitPerLeaf,
            $sortBy,
            $sortDir,
            $foreignKey,
            $relationName,
            $leafIdResolver,
            $groupByResolver,
        ) {
            $posts = $this->fetchPostsForItem(
                $item,
                $foreignKey,
                $relationName,
                $sortBy,
                $sortDir,
                $limit,
                $limitPerLeaf,
                $leafIdResolver,
                $groupByResolver,
            );

            return $carry->merge($posts);
        }, collect());

        return $this->finalizePostsCollection($allPosts, $sortBy, $sortDir, $limit);
    }

    /**
     * Fetch hierarchy items with their children.
     */
    protected function fetchHierarchyItems(string $modelClass, array $ids): Collection
    {
        return $modelClass::with('children')
            ->whereIn('id', $ids)
            ->get();
    }

    /**
     * Fetch posts for a single hierarchy item.
     */
    protected function fetchPostsForItem(
        mixed $item,
        ?string $foreignKey,
        string $relationName,
        string $sortBy,
        string $sortDir,
        int $limit,
        int $limitPerLeaf,
        ?callable $leafIdResolver,
        ?callable $groupByResolver
    ): Collection {
        $leafIds = $this->resolveLeafIds($item, $leafIdResolver);
        $query   = $this->buildPostQuery($leafIds, $foreignKey, $relationName, $sortBy, $sortDir);

        if ($item->children->isEmpty()) {
            return $query->limit($limit)->get();
        }

        $posts = $query->get()
            ->groupBy($groupByResolver ?? fn(Post $p) => $this->resolveGroupByKey($p, $foreignKey, $relationName))
            ->flatMap(fn(Collection $group) => $group->take($limitPerLeaf));

        return $posts->values();
    }

    /**
     * Build the base post query for the hierarchy.
     */
    protected function buildPostQuery(
        array $leafIds,
        ?string $foreignKey,
        string $relationName,
        string $sortBy,
        string $sortDir
    ): Builder {
        return Post::query()
            ->published()
            ->when($foreignKey, fn(Builder $q) => $q->whereIn($foreignKey, $leafIds))
            ->when(
                !$foreignKey,
                fn(Builder $q) => $q->whereHas($relationName, fn(Builder $b) => $b->whereIn($relationName . '.id', $leafIds))
            )
            ->orderBy($sortBy, $sortDir);
    }

    /**
     * Finalize the full post collection with sorting, limiting, and uniqueness.
     */
    protected function finalizePostsCollection(Collection $posts, string $sortBy, string $sortDir, int $limit): Collection
    {
        return $posts
            ->unique('id')
            ->sortBy($sortBy, SORT_REGULAR, $sortDir === 'desc')
            ->take($limit)
            ->values();
    }

    /**
     * Resolve the leaf IDs for a hierarchy item.
     */
    protected function resolveLeafIds(mixed $item, ?callable $leafIdResolver): array
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
     * Resolve the grouping key for posts.
     */
    protected function resolveGroupByKey(Post $post, ?string $foreignKey, string $relationName): int|string
    {
        return $foreignKey
            ? $post->{$foreignKey}
            : $post->{$relationName}->first()->id;
    }
}
