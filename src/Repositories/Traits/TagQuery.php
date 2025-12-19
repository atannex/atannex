<?php

namespace Atannex\Repositories\Traits;

use App\Enums\Flag;
use App\Models\Regions\Category;
use App\Models\Tags\Tag;
use Atannex\Traits\HasTree;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

trait TagQuery
{
    use HasTree;

    protected const PAGINATION_LIMIT = 15;
    protected const POPULAR_LIMIT = 12;

    /**
     * Get posts filtered by tag.
     */
    public function postsByTag(Tag $tag, int $limit = self::PAGINATION_LIMIT): LengthAwarePaginator
    {
        return $tag->posts()
            ->flagged(Flag::PUBLISHED)
            ->latest()
            ->paginate($limit);
    }

    /**
     * Popular tags inside the Tag's category tree.
     */
    public function popularTags(Tag $tag, int $limit = self::POPULAR_LIMIT): Collection
    {
        $root = $this->getRoot($this->resolveTagCategory($tag));
        $treeIds = $this->getTreeIds($root);

        return Tag::query()
            ->whereHas(
                'posts',
                fn($q) => $q
                    ->flagged(Flag::PUBLISHED)
                    ->whereIn('category_id', $treeIds)
            )
            ->withCount([
                'posts' => fn($q) => $q
                    ->flagged(Flag::PUBLISHED)
                    ->whereIn('category_id', $treeIds),
            ])
            ->orderByDesc('posts_count')
            ->limit($this->sanitizeLimit($limit))
            ->get();
    }

    /**
     * Leaf category list under tag's category tree.
     */
    public function relatedCategoriesByTag(Tag $tag, int $limit = self::PAGINATION_LIMIT): Collection
    {
        return $this->getLeafNodes(
            $this->getRoot($this->resolveTagCategory($tag)),
            'posts',
            Flag::PUBLISHED,
            null,
            $limit
        );
    }

    /**
     * A tag resolves its primary category via its first associated post.
     */
    protected function resolveTagCategory(Tag $tag): Category
    {
        return $tag->posts()
            ->flagged(Flag::PUBLISHED)
            ->with('category.parent')
            ->firstOrFail()
            ->category;
    }
}
