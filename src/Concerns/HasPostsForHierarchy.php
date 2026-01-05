<?php

namespace Atannex\Concerns;

use App\Enums\Sorting;
use App\Models\Posts\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Trait HasPostsForHierarchy
 *
 * Fetch posts across hierarchical models using a strictly limited
 * and validated set of sorting options.
 */
trait HasPostsForHierarchy
{
    /**
     * Public entry point
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
        $limit          = (int) ($options['limit'] ?? 10);
        $limitPerLeaf   = (int) ($options['leaf_relation_limit'] ?? 3);

        ['key' => $sortBy, 'dir' => $sortDir] = $this->resolveSorting($options);

        $items = $this->fetchHierarchyItems($modelClass, $ids);

        $allPosts = $items->reduce(function (Collection $carry, $item) use (
            $foreignKey,
            $relationName,
            $sortBy,
            $sortDir,
            $limit,
            $limitPerLeaf,
            $leafIdResolver,
            $groupByResolver
        ) {
            return $carry->merge(
                $this->fetchPostsForItem(
                    $item,
                    $foreignKey,
                    $relationName,
                    $sortBy,
                    $sortDir,
                    $limit,
                    $limitPerLeaf,
                    $leafIdResolver,
                    $groupByResolver
                )
            );
        }, collect());

        return $this->finalizePostsCollection(
            $allPosts,
            $sortBy,
            $sortDir,
            $limit
        );
    }

    /**
     * Allowed sortable columns (single source of truth)
     */
    protected function sortableColumns(): array
    {
        return [
            Sorting::PUBLISHED_AT => 'published_at',
            Sorting::CREATED_AT  => 'created_at',
            Sorting::UPDATED_AT  => 'updated_at',
            Sorting::COMMENTS    => 'comments_count',
            Sorting::CATEGORY    => 'category_id',
            Sorting::REGION      => 'region',
        ];
    }

    /**
     * Strict sorting resolver
     */
    protected function resolveSorting(array $options): array
    {
        $sort = $options['sort'] ?? Sorting::PUBLISHED_AT;
        $dir  = strtolower($options['order'] ?? 'desc');

        $allowed = array_keys($this->sortableColumns());

        return [
            'key' => in_array($sort, $allowed, true)
                ? $sort
                : Sorting::PUBLISHED_AT,

            'dir' => in_array($dir, ['asc', 'desc'], true)
                ? $dir
                : 'desc',
        ];
    }

    /**
     * Fetch hierarchy root items
     */
    protected function fetchHierarchyItems(
        string $modelClass,
        array $ids
    ): Collection {
        return $modelClass::with('children')
            ->whereIn('id', $ids)
            ->get();
    }

    /**
     * Fetch posts for a single hierarchy item
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

        $query = $this->buildPostQuery(
            $leafIds,
            $foreignKey,
            $relationName,
            $sortBy,
            $sortDir
        );

        if ($item->children->isEmpty()) {
            return $query->limit($limit)->get();
        }

        return $query->get()
            ->groupBy(
                $groupByResolver
                    ?? fn(Post $p) => $this->resolveGroupByKey(
                        $p,
                        $foreignKey,
                        $relationName
                    )
            )
            ->flatMap(fn(Collection $group) => $group->take($limitPerLeaf))
            ->values();
    }

    /**
         * Create a base Eloquent query for posts constrained to the provided leaf IDs and prepared for sorting and thresholds.
         *
         * The query is scoped to published posts, includes a `comments_count` aggregate, and is restricted to the supplied
         * leaf IDs either by applying a `whereIn` on the provided foreign key or by using `whereHas` on the given relation.
         *
         * @param int[] $leafIds IDs of leaf items used to restrict which posts are considered.
         * @param string|null $foreignKey Optional posts table foreign key column that references leaf items; when provided the query uses `whereIn` on this column.
         * @param string $relationName Name of the relation on the Post model that links to leaf items; used when `$foreignKey` is null.
         * @param string $sortBy Sorting key (one of the allowed sortable columns) to apply via sorting/threshold rules.
         * @param string $sortDir Sorting direction, either 'asc' or 'desc'.
         * @return Builder The configured post query builder with published scope, comment counts, leaf filters, and sorting/thresholds applied.
         */
    protected function buildPostQuery(
        array $leafIds,
        ?string $foreignKey,
        string $relationName,
        string $sortBy,
        string $sortDir
    ): Builder {
        $query = Post::query()
            ->published()
            ->withCount([
                'comments as comments_count',
            ])
            ->when(
                $foreignKey,
                fn(Builder $q) => $q->whereIn($foreignKey, $leafIds)
            )
            ->when(
                ! $foreignKey,
                fn(Builder $q) => $q->whereHas(
                    $relationName,
                    fn(Builder $b) =>
                    $b->whereIn($relationName . '.id', $leafIds)
                )
            );

        return $this->applySortingAndThresholds(
            $query,
            $sortBy,
            $sortDir
        );
    }

    /**
     * Apply sorting and minimal thresholds
     */
    protected function applySortingAndThresholds(
        Builder $query,
        string $sortBy,
        string $sortDir
    ): Builder {
        if ($sortBy === Sorting::COMMENTS) {
            $query->has('comments', '>=', 3);
        }

        $column = $this->sortableColumns()[$sortBy] ?? 'published_at';

        return $query->orderBy($column, $sortDir);
    }

    /**
     * Final in-memory sort & limit
     */
    protected function finalizePostsCollection(
        Collection $posts,
        string $sortBy,
        string $sortDir,
        int $limit
    ): Collection {
        $column = $this->sortableColumns()[$sortBy] ?? 'published_at';

        $posts = $posts->sortBy(
            $column,
            SORT_REGULAR,
            $sortDir === 'desc'
        );

        return $posts
            ->unique('id')
            ->take($limit)
            ->values();
    }

    /**
     * Resolve leaf IDs
     */
    protected function resolveLeafIds(
        mixed $item,
        ?callable $leafIdResolver
    ): array {
        if ($item->children->isEmpty()) {
            return [$item->id];
        }

        return $leafIdResolver
            ? $leafIdResolver($item)
            : $item->getDescendants()
            ->where(fn($child) => $child->children->isEmpty())
            ->pluck('id')
            ->all();
    }

    /**
     * Resolve grouping key
     */
    protected function resolveGroupByKey(
        Post $post,
        ?string $foreignKey,
        string $relationName
    ): int|string {
        return $foreignKey
            ? $post->{$foreignKey}
            : $post->{$relationName}->first()?->id;
    }
}