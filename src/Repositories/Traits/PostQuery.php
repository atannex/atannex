<?php

namespace Atannex\Repositories\Traits;

use App\Models\Posts\Post;
use App\Models\Regions\Category;
use App\Models\Regions\Region;
use Atannex\Concerns\HasResolver;
use Atannex\Traits\HasTree;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

trait PostQuery
{
    use HasResolver;
    use HasTree;

    protected const PAGINATION_LIMIT = 15;

    protected const RECENT_LIMIT = 5;

    /**
     * Paginate posts under any Category tree.
     */
    /**
     * Paginate posts assigned to the given Category and its full subtree.
     */
    public function postsByCategory(Category $category, int $limit = self::PAGINATION_LIMIT): LengthAwarePaginator
    {
        $categoryIds = $this->getTreeIds($category);

        return Post::published()
            ->whereIn('category_id', $categoryIds)
            ->with($this->postRelations())
            ->latest()
            ->paginate($limit);
    }


    /**
     * Recent posts from within same tree — excluding the current post.
     */
    public function recentPosts(?Post $post = null, int $limit = self::RECENT_LIMIT): Collection
    {
        $query = Post::published()
            ->with($this->postRelations())
            ->latest('published_at')
            ->limit($this->sanitizeLimit($limit));

        if ($post) {
            $query->whereIn('category_id', $this->getTreeIds($this->getRoot($post->category)))
                ->where('id', '!=', $post->id);
        }

        return $query->get();
    }


    /**
     * Paginate posts under a Region tree (leaf-category filtering).
     */
    public function postsByRegion(Region $region, int $limit = self::PAGINATION_LIMIT): LengthAwarePaginator
    {
        return $this->paginate(
            Post::query()
                ->published()
                ->whereHas('regions', fn($q) => $q->whereIn('region_id', $this->getTreeIds($region)))
                ->with($this->postRelations()),
            $limit
        );
    }

    /**
     * Paginate posts by archive year and/or month.
     */
    public function postsByDate(string $yearMonth, int $limit = self::PAGINATION_LIMIT): LengthAwarePaginator
    {
        [$year, $month] = $this->extractDateParts($yearMonth);

        $query = Post::published()
            ->when($year, fn($q) => $q->whereYear('published_at', $year))
            ->when($month, fn($q) => $q->whereMonth('published_at', $month))
            ->with($this->postRelations());

        return $this->paginate($query, $limit);
    }


    /**
     * Paginate posts by author.
     */
    public function postsByAuthor(string $slug, int $limit = self::PAGINATION_LIMIT): LengthAwarePaginator
    {
        $author = $this->resolveAuthor($slug);

        return $this->paginate(
            Post::published()
                ->where('author_id', $author->id)
                ->with($this->postRelations()),
            $limit
        );
    }
}
