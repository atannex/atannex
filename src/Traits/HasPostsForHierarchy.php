<?php

namespace Atannex\Traits;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

trait HasPostsForHierarchy
{
    protected function fetchPostsForHierarchy(
        array $ids,
        array $options,
        string $modelClass,
        string $relationName,
        ?string $foreignKey = null,
        ?callable $leafIdResolver = null,
        ?callable $groupByResolver = null
    ): Collection {
        $limit = max(1, (int) ($options['limit'] ?? 10));
        $limitPerLeafPost = max(1, (int) ($options['limit_per_leaf_post'] ?? 1));
        $sortBy = $options['sort'] ?? 'published_at';
        $sortDir = $options['order'] ?? 'desc';

        return $modelClass::with('children')
            ->whereIn('id', $ids)
            ->get()
            ->reduce(function (Collection $allPosts, $item) use (
                $limit,
                $limitPerLeafPost,
                $sortBy,
                $sortDir,
                $foreignKey,
                $relationName,
                $leafIdResolver,
                $groupByResolver,
            ) {
                $leafIds = $this->resolveLeafIds($item, $leafIdResolver);

                $query = Post::published()
                    ->when($foreignKey, fn($q) => $q->whereIn($foreignKey, $leafIds))
                    ->when(!$foreignKey, fn($q) => $q->whereHas(
                        $relationName,
                        fn(Builder $builder) => $builder->whereIn($relationName . '.id', $leafIds)
                    ))
                    ->orderBy($sortBy, $sortDir);

                $posts = $item->children->isEmpty()
                    ? $query->take($limit)->get()
                    : $query->get()
                    ->groupBy($groupByResolver ?? fn(Post $p) => $this->resolveGroupByKey($p, $foreignKey, $relationName))
                    ->flatMap(fn($group) => $group->take($limitPerLeafPost));

                return $allPosts->merge($posts);
            }, collect())
            ->unique('id')
            ->sortBy($sortBy, SORT_REGULAR, $sortDir === 'desc')
            ->take($limit)
            ->values();
    }

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

    private function resolveGroupByKey(Post $post, ?string $foreignKey, string $relationName)
    {
        return $foreignKey ? $post->{$foreignKey} : $post->{$relationName}->first()->id;
    }
}
