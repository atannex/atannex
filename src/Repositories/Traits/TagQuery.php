<?php

namespace Atannex\Repositories\Traits;

use App\Models\Tags\Tag;
use App\Models\Posts\Post;
use App\Models\Regions\Category;
use Atannex\Traits\HasTree;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

trait TagQuery
{
    use HasTree;

    protected const PAGINATION_LIMIT = 15;

    protected const POPULAR_LIMIT = 12;

    /**
     * Posts filtered by tag.
     */
    public function postsByTag(Tag $tag, int $limit = self::PAGINATION_LIMIT): LengthAwarePaginator
    {
        return $this->paginate(
            Post::published()
                ->whereHas('tags', fn($q) => $q->whereKey($tag->id))
                ->with($this->postRelations()),
            $limit
        );
    }

    /**
     * Popular tags inside the Tag's category tree.
     */
    public function popularTags(Tag $tag, int $limit = self::POPULAR_LIMIT): Collection
    {
        $root = $this->getRoot($this->resolveTagCategory($tag));
        $treeIds = $this->getTreeIds($root);

        return Tag::query()
            ->whereHas('posts', fn($q) => $q->whereIn('category_id', $treeIds))
            ->withCount([
                'posts' => fn($q) => $q->whereIn('category_id', $treeIds)
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
            node: $this->getRoot($this->resolveTagCategory($tag)),
            countRelation: 'posts',
            countFilter: 'published',
            excludeId: null,
            limit: $limit
        );
    }

    /**
     * A tag resolves its primary category via its first associated post.
     */
    protected function resolveTagCategory(Tag $tag): Category
    {
        return $tag->posts()
            ->with('category.parent')
            ->firstOrFail()
            ->category;
    }
}
