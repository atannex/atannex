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
        string|null $foreignKey = null,
        callable|null $leafIdResolver = null,
        callable|null $groupByResolver = null
    ): Collection {
        $limit            = max(1, (int) ($options['limit'] ?? 10));
        $limitPerLeafPost = max(1, (int) ($options['limit_per_leaf_post'] ?? 1));
        $sortBy           = $options['sort_by'] ?? $options['sort'] ?? 'published_at';
        $sortDir          = $options['sort_dir'] ?? $options['order'] ?? 'desc';

        if ($ids === []) {
            return collect();
        }

        $items    = $modelClass::with('children')->whereIn('id', $ids)->get();
        $allPosts = collect();

        foreach ($items as $item) {
            if ($item->children->isEmpty()) {
                $posts = Post::published()
                    ->when($foreignKey, fn($q) => $q->where($foreignKey, $item->id))
                    ->when(!$foreignKey, fn($q) => $q->whereHas(
                        $relationName,
                        fn(Builder $builder) => $builder->where("{$relationName}.id", $item->id)
                    ))
                    ->orderBy($sortBy, $sortDir)
                    ->take($limit)
                    ->get();

                $allPosts = $allPosts->merge($posts);
            } else {
                $leafIds = $leafIdResolver
                    ? $leafIdResolver($item)
                    : $item->getDescendants()
                    ->filter(fn($child) => $child->children->isEmpty())
                    ->pluck('id')
                    ->all();

                if (empty($leafIds)) {
                    continue;
                }

                $posts = Post::published()
                    ->when($foreignKey, fn($q) => $q->whereIn($foreignKey, $leafIds))
                    ->when(!$foreignKey, fn($q) => $q->whereHas(
                        $relationName,
                        fn(Builder $builder) => $builder->whereIn("{$relationName}.id", $leafIds)
                    ))
                    ->orderBy($sortBy, $sortDir)
                    ->get()
                    ->groupBy($groupByResolver ?? fn(Post $p) => $foreignKey ? $p->{$foreignKey} : $p->{$relationName}->first()->id)
                    ->flatMap(fn($group) => $group->take($limitPerLeafPost));

                $allPosts = $allPosts->merge($posts);
            }
        }

        return $allPosts
            ->unique('id')
            ->sortBy([$sortBy => $sortDir === 'desc' ? SORT_DESC : SORT_ASC])
            ->values();
    }
}
