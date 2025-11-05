<?php

namespace Atannex\Repositories;

use App\Models\Tags\Tag;
use Illuminate\Database\Eloquent\Model;
use App\Models\Posts\Post;
use App\Models\Regions\Category;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Atannex\Contracts\CategoryInterface;
use Atannex\Repositories\Traits\TagQuery;
use Atannex\Repositories\Traits\PostQuery;
use Atannex\Repositories\Traits\CategoryTree;
use Atannex\Helpers\HasMedia;

class CategoryRepository implements CategoryInterface
{
    use CategoryTree;
    use PostQuery;
    use TagQuery;
    use HasMedia;

    /**
     * Get root category of a tag (based on first post assigned)
     */
    protected function rootCategoryFromTag(Tag $tag): Category
    {
        return $this->root(
            $tag->posts()
                ->with('category.parent')
                ->firstOrFail()
                ->category
        );
    }

    /**
     * Get all tree category IDs from Tag
     */
    protected function categoryTreeIdsFromTag(Tag $tag): Collection
    {
        return $this->treeIds(
            $this->rootCategoryFromTag($tag)
        );
    }

    /**
     * Build a published post query under a category tree.
     */
    protected function buildPostQuery(Category $category, ?int $excludeId = null): Builder
    {
        return Post::published()
            ->whereIn('category_id', $this->treeIds($category, $excludeId))
            ->with($this->defaultRelations())
            ->latest('published_at');
    }

    /**
     * Get all category IDs under the same tree.
     */
    protected function treeIds(Model $model, ?int $excludeId = null): Collection
    {
        $ids = $model->getDescendants()
            ->pluck('id')
            ->push($model->id)
            ->unique()
            ->values();

        return $excludeId
            ? $ids->reject(fn($id) => $id === $excludeId)->values()
            : $ids;
    }

    /**
     * Get the root (top ancestor) of the category.
     */
    protected function root(Category $category): Category
    {
        $ancestors = $category->getAncestors();

        return $ancestors->isNotEmpty()
            ? $ancestors->last()
            : $category;
    }

    /**
     * Retrieve leaf categories ordered by published post count
     */
    protected function leafCategories(Category $ancestor, ?int $excludeId = null, int $limit = 12): Collection
    {
        return Category::whereIn('id', $this->treeIds($ancestor, $excludeId))
            ->whereDoesntHave('children')
            ->withCount(['posts' => fn($q) => $q->published()])
            ->orderByDesc('posts_count')
            ->limit($this->sanitizeLimit($limit))
            ->get();
    }

    /**
     * Paginate posts with safe limit
     */
    protected function paginatePosts(Builder $query, int $limit): LengthAwarePaginator
    {
        return $query->paginate($this->sanitizeLimit($limit));
    }

    /**
     * Allowed eager relations for posts
     */
    private function defaultRelations(): array
    {
        return [
            'category',
            'tags',
            'author.user'
        ];
    }

    /**
     * Ensure positive pagination limit
     */
    private function sanitizeLimit(int $limit): int
    {
        return max(1, $limit);
    }
}
