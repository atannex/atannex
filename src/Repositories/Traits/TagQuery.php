<?php

namespace Atannex\Repositories\Traits;

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
     * Retrieve published posts associated with a given tag, ordered newest first.
     *
     * @param Tag $tag The tag whose posts to retrieve.
     * @param int $limit Maximum number of posts per page.
     * @return LengthAwarePaginator A paginator of the tag's published posts ordered by newest first.
     */
    public function postsByTag(Tag $tag, int $limit = self::PAGINATION_LIMIT): LengthAwarePaginator
    {
        return $tag->posts()
            ->published()
            ->latest()
            ->paginate($limit);
    }

    /**
     * Get popular tags within the tag's category tree.
     *
     * Tags are ordered by descending count of published posts scoped to the resolved category tree.
     *
     * @param Tag $tag The tag whose category tree defines the scope for popularity.
     * @param int $limit Maximum number of tags to return.
     * @return Collection Collection of Tag models with a `posts_count` attribute representing the number of published posts for each tag inside the category tree.
     */
    public function popularTags(Tag $tag, int $limit = self::POPULAR_LIMIT): Collection
    {
        $root = $this->getRoot($this->resolveTagCategory($tag));
        $treeIds = $this->getTreeIds($root);

        return Tag::query()
            ->whereHas(
                'posts',
                fn($q) => $q
                    ->published()
                    ->whereIn('category_id', $treeIds)
            )
            ->withCount([
                'posts' => fn($q) => $q
                    ->published()
                    ->whereIn('category_id', $treeIds),
            ])
            ->orderByDesc('posts_count')
            ->limit($this->sanitizeLimit($limit))
            ->get();
    }

    /**
     * Retrieve leaf categories under the tag's category tree that have published posts.
     *
     * @param Tag $tag The tag whose category tree is used to find related leaf categories.
     * @param int $limit The maximum number of categories to return.
     * @return Collection A collection of leaf Category models related to the tag's category tree.
     */
    public function relatedCategoriesByTag(Tag $tag, int $limit = self::PAGINATION_LIMIT): Collection
    {
        return $this->getLeafNodes(
            $this->getRoot($this->resolveTagCategory($tag)),
            'posts',
            'published',
            null,
            $limit
        );
    }

    /**
     * Determine the tag's primary Category from its first published post.
     *
     * @return Category The Category associated with the tag's first published post.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the tag has no published posts.
     */
    protected function resolveTagCategory(Tag $tag): Category
    {
        return $tag->posts()
            ->published()
            ->with('category.parent')
            ->firstOrFail()
            ->category;
    }
}