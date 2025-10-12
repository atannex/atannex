<?php

declare(strict_types=1);

namespace Atannex\Traits;

use App\Enums\Sorting;
use App\Models\Posts\Post;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait HasPostsForHierarchy
 *
 * Fetches posts across hierarchical models using enum-based sorting
 * tailored for news websites with engagement and editorial relevance.
 */
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
        $limit        = (int) ($options['limit'] ?? 10);
        $limitPerLeaf = (int) ($options['leaf_relation_limit'] ?? 3);
        $sortKey      = $options['sort'] ?? Sorting::PUBLISHED_AT;
        $sortDir      = strtolower($options['order'] ?? 'desc');

        $sortBy = Sorting::hasValue($sortKey) ? $sortKey : Sorting::PUBLISHED_AT;

        $items = $this->fetchHierarchyItems($modelClass, $ids);

        $allPosts = $items->reduce(function (Collection $carry, $item) use (
            $foreignKey,
            $relationName,
            $sortBy,
            $sortDir,
            $limit,
            $limitPerLeaf,
            $leafIdResolver,
            $groupByResolver,
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

        return $this->finalizePostsCollection($allPosts, $sortBy, $sortDir, $limit);
    }

    protected function fetchHierarchyItems(string $modelClass, array $ids): Collection
    {
        return $modelClass::with('children')
            ->whereIn('id', $ids)
            ->get();
    }

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

        return $query->get()
            ->groupBy($groupByResolver ?? fn(Post $p) => $this->resolveGroupByKey($p, $foreignKey, $relationName))
            ->flatMap(fn(Collection $group) => $group->take($limitPerLeaf))
            ->values();
    }

    /**
     * Build the base post query with counts, averages, and thresholds.
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
                'views as views_count' => fn($q) => $q->latest('viewed_at'),
                'likes as likes_count' => fn($q) => $q->latest('liked_at'),
                'shares as shares_count' => fn($q) => $q->latest('shared_at'),
                'ratings as rates_count' => fn($q) => $q->latest('rated_at'),
                'comments as comments_count' => fn($q) => $q->latest('created_at'),
            ])
            ->withAvg('ratings as average_rating', 'rating')
            ->when($foreignKey, fn(Builder $q) => $q->whereIn($foreignKey, $leafIds))
            ->when(
                !$foreignKey,
                fn(Builder $q) =>
                $q->whereHas($relationName, fn(Builder $b) => $b->whereIn($relationName . '.id', $leafIds))
            );

        return $this->applySortingAndThresholds($query, $sortBy, $sortDir);
    }

    /**
     * Apply enum-based sorting and minimum engagement thresholds.
     */
    protected function applySortingAndThresholds(Builder $query, string $sortBy, string $sortDir): Builder
    {
        $thresholds = [
            Sorting::VIEWS     => ['relation' => 'views', 'column' => 'views_count', 'min' => 10],
            Sorting::LIKES     => ['relation' => 'likes', 'column' => 'likes_count', 'min' => 5],
            Sorting::SHARES    => ['relation' => 'shares', 'column' => 'shares_count', 'min' => 5],
            Sorting::COMMENTS  => ['relation' => 'comments', 'column' => 'comments_count', 'min' => 3],
            Sorting::RATING    => ['relation' => 'ratings', 'column' => 'average_rating', 'min' => 2],
        ];

        if (isset($thresholds[$sortBy])) {
            $t = $thresholds[$sortBy];
            $query->has($t['relation'], '>=', $t['min']);
        }

        switch ($sortBy) {
            case Sorting::PUBLISHED_AT:
            case Sorting::CREATED_AT:
            case Sorting::UPDATED_AT:
                $query->orderBy($sortBy, $sortDir);
                break;

            case Sorting::VIEWS:
                $query->orderBy('views_count', $sortDir);
                break;

            case Sorting::COMMENTS:
                $query->orderBy('comments_count', $sortDir);
                break;

            case Sorting::LIKES:
                $query->orderBy('likes_count', $sortDir);
                break;

            case Sorting::SHARES:
                $query->orderBy('shares_count', $sortDir);
                break;

            case Sorting::RATING:
                $query->orderBy('average_rating', $sortDir);
                break;

            case Sorting::TITLE:
                $query->orderBy('title', $sortDir);
                break;

            case Sorting::AUTHOR:
                $query->orderBy('author_id', $sortDir);
                break;

            case Sorting::READING_TIME:
                $query->orderBy('reading_time', $sortDir);
                break;

            case Sorting::CATEGORY:
                $query->orderBy('category_id', $sortDir);
                break;

            case Sorting::FEATURED:
                $query->orderByDesc('is_featured');
                break;

            case Sorting::TRENDING:
                $query->orderByRaw('(views_count + shares_count + likes_count) ' . strtoupper($sortDir));
                break;

            case Sorting::BREAKING_PRIORITY:
                $query->orderByDesc('breaking_priority');
                break;

            case Sorting::EDITOR_PICK:
                $query->orderByDesc('flag');
                break;

            case Sorting::SOURCE_CREDIBILITY:
                $query->orderBy('source_credibility_score', $sortDir);
                break;

            case Sorting::REGION:
                $query->orderBy('region', $sortDir);
                break;

            case Sorting::HEADLINE_LENGTH:
                $query->orderByRaw('CHAR_LENGTH(title) ' . strtoupper($sortDir));
                break;

            case Sorting::RELEVANCE:
                $query->orderBy('relevance_score', $sortDir);
                break;

            case Sorting::POPULARITY:
                $query->orderByRaw('(views_count + likes_count + shares_count + comments_count) DESC');
                break;

            case Sorting::RANDOM:
                $query->inRandomOrder();
                break;

            default:
                $query->latest('published_at');
                break;
        }

        return $query;
    }

    protected function finalizePostsCollection(Collection $posts, string $sortBy, string $sortDir, int $limit): Collection
    {
        $columnMap = [
            Sorting::PUBLISHED_AT => 'published_at',
            Sorting::CREATED_AT   => 'created_at',
            Sorting::UPDATED_AT   => 'updated_at',
            Sorting::VIEWS        => 'views_count',
            Sorting::COMMENTS     => 'comments_count',
            Sorting::LIKES        => 'likes_count',
            Sorting::SHARES       => 'shares_count',
            Sorting::RATING       => 'average_rating',
            Sorting::TITLE        => 'title',
            Sorting::AUTHOR       => 'author_id',
            Sorting::READING_TIME => 'reading_time',
            Sorting::CATEGORY     => 'category_id',
            Sorting::REGION       => 'region',
        ];

        $column = $columnMap[$sortBy] ?? 'published_at';

        if ($sortBy === Sorting::POPULARITY || $sortBy === Sorting::TRENDING) {
            $posts = $posts->sortByDesc(
                fn($p) =>
                $p->views_count + $p->likes_count + $p->shares_count + $p->comments_count
            );
        } elseif ($sortBy === Sorting::FEATURED) {
            $posts = $posts->sortByDesc(fn($p) => $p->is_featured);
        } elseif ($sortBy === Sorting::EDITOR_PICK) {
            $posts = $posts->sortByDesc(fn($p) => $p->is_editor_pick);
        } elseif ($sortBy === Sorting::HEADLINE_LENGTH) {
            $posts = $posts->sortBy(
                fn($p) => mb_strlen($p->title),
                SORT_REGULAR,
                $sortDir === 'desc'
            );
        } elseif ($sortBy !== Sorting::RANDOM) {
            $posts = $posts->sortBy($column, SORT_REGULAR, $sortDir === 'desc');
        } else {
            $posts = $posts->shuffle();
        }

        return $posts->unique('id')->take($limit)->values();
    }

    protected function resolveLeafIds(mixed $item, ?callable $leafIdResolver): array
    {
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

    protected function resolveGroupByKey(Post $post, ?string $foreignKey, string $relationName): int|string
    {
        return $foreignKey
            ? $post->{$foreignKey}
            : optional($post->{$relationName}->first())->id;
    }
}
