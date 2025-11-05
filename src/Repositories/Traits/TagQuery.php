<?php

namespace Atannex\Repositories\Traits;

use App\Models\Tags\Tag;
use App\Models\Posts\Post;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

trait TagQuery
{
    protected const PAGINATION_LIMIT = 15;

    protected const POPULAR_LIMIT = 12;

    /**
     * Posts filtered by tag.
     */
    public function postsByTag(Tag $tag, int $limit = self::PAGINATION_LIMIT): LengthAwarePaginator
    {
        return Post::published()
            ->whereHas('tags', fn($q) => $q->whereKey($tag->id))
            ->with($this->defaultRelations())
            ->latest()
            ->paginate($this->sanitizeLimit($limit));
    }

    /**
     * Popular tags inside same category tree.
     */
    public function popularTags(Tag $tag, int $limit = self::POPULAR_LIMIT): Collection
    {
        return Tag::query()
            ->whereHas('posts', fn($q) => $q->whereIn('category_id', $this->categoryTreeIdsFromTag($tag)))
            ->withCount(['posts' => fn($q) => $q->whereIn('category_id', $this->categoryTreeIdsFromTag($tag))])
            ->orderByDesc('posts_count')
            ->limit($this->sanitizeLimit($limit))
            ->get();
    }

    /**
     * Leaf categories related to tag.
     */
    public function relatedCategoriesByTag(Tag $tag, int $limit = self::PAGINATION_LIMIT): Collection
    {
        return $this->leafCategories($this->rootCategoryFromTag($tag), null, $limit);
    }
}
