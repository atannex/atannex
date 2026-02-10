<?php

declare(strict_types=1);

namespace Atannex\Services;

use App\Enums\Flag;
use App\Models\Tags\Tag;
use App\Models\Posts\Post;
use App\Models\Regions\Region;
use App\Models\Regions\Category;
use App\Models\Regions\Employee;
use App\Models\Others\SocialMedia;
use Atannex\Traits\HasTree;
use Atannex\Helpers\HasMedia;
use Atannex\Concerns\HasResolver;
use Atannex\Traits\HandlesPostDateResolution;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

final class CategoryService
{
    use HasMedia;
    use HasTree;
    use HasResolver;
    use HandlesPostDateResolution;

    /*
    |--------------------------------------------------------------------------
    | Limits
    |--------------------------------------------------------------------------
    */
    protected const CATEGORY_LIMIT   = 12;
    protected const POPULAR_LIMIT    = 12;
    protected const PAGINATION_LIMIT = 15;
    protected const RECENT_LIMIT     = 5;

    /*
    |--------------------------------------------------------------------------
    | Employee
    |--------------------------------------------------------------------------
    */

    public function employeeSocial(Employee $employee): Collection
    {
        return $employee->socialMedia()
            ->nonGlobal()
            ->ordered()
            ->get()
            ->map(fn(SocialMedia $media) => $this->mapSocialMedia($media))
            ->filter()
            ->values();
    }


    public function relatedCategories(Category $category, int $limit = self::CATEGORY_LIMIT): Collection
    {
        return $this->getLeafNodes(
            $this->getRoot($category),
            'posts',
            Flag::PUBLISHED,
            $category->id,
            $limit
        );
    }

    public function postsByCategory(Category $category, int $limit = self::PAGINATION_LIMIT): LengthAwarePaginator
    {
        return Post::query()
            ->published()
            ->whereIn('category_id', $this->getTreeIds($category))
            ->with('category.parent')
            ->latest('published_at')
            ->paginate($limit);
    }

    public function recentPostsByCategory(Category $category, int $limit = 6): Collection
    {
        return Post::query()
            ->published()
            ->whereIn('category_id', $this->getTreeIds($this->getRoot($category)))
            ->with([
                'category.parent',
                'region',
                'tags',
                'author.user',
            ])
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    public function postsByDate(string $yearMonth, int $limit = self::PAGINATION_LIMIT): LengthAwarePaginator
    {
        $resolution = $this->resolvePostArchiveBySlug($yearMonth);

        if ($resolution === null) {
            return Post::query()
                ->whereRaw('1 = 0')
                ->paginate($limit);
        }

        return Post::published()
            ->when(
                $resolution['type'] === 'year',
                fn($query) => $query->whereYear('published_at', $resolution['year'])
            )
            ->when(
                $resolution['type'] === 'month',
                fn($query) => $query
                    ->whereYear('published_at', $resolution['year'])
                    ->whereMonth('published_at', $resolution['month'])
            )
            ->with([
                'category.parent',
                'region',
                'tags',
                'author.user',
            ])
            ->latest('published_at')
            ->paginate($limit);
    }




    public function postsByTag(Tag $tag, int $limit = self::PAGINATION_LIMIT): LengthAwarePaginator
    {
        return $tag->posts()
            ->published()
            ->latest()
            ->paginate($limit);
    }

    public function popularTagsByCategory(Category $category, int $limit = self::POPULAR_LIMIT): Collection
    {
        $treeIds = $this->getTreeIds($this->getRoot($category));

        return Tag::query()
            ->withCount([
                'posts as usage_count' => fn($q) =>
                $q->published()->whereIn('category_id', $treeIds),
            ])
            ->having('usage_count', '>', 0)
            ->orderByDesc('usage_count')
            ->limit($limit)
            ->get();
    }

    public function popularTagsByRegion(Region $region, int $limit = 8): Collection
    {
        return Tag::query()
            ->whereHas('posts', function ($query) use ($region) {
                $query->published()
                    ->where('region_id', $region->getKey());
            })
            ->withCount(['posts as posts_count' => function ($query) use ($region) {
                $query->published()
                    ->where('region_id', $region->getKey());
            }])
            ->orderByDesc('posts_count')
            ->limit($limit)
            ->get();
    }

    public function relatedCategoriesByTag(?Tag $tag, int $limit = self::PAGINATION_LIMIT): Collection
    {
        $categoryIds = $tag->posts()
            ->published()
            ->pluck('category_id')
            ->unique();

        return Category::query()
            ->whereIn('id', $categoryIds)
            ->whereDoesntHave('children')
            ->limit($limit)
            ->get();
    }

    public function relatedCategoriesByRegion(Region $region): Collection
    {
        return Category::query()
            ->whereHas('posts', function ($query) use ($region) {
                $query->published()
                    ->where('region_id', $region->getKey());
            })
            ->withCount(['posts as posts_count' => function ($query) use ($region) {
                $query->published()
                    ->where('region_id', $region->getKey());
            }])
            ->orderByDesc('posts_count')
            ->get();
    }

    public function relatedCategoriesByPost(Post $post, int $limit = 6): Collection
    {
        return Category::query()
            ->whereKeyNot($post->category_id)
            ->whereHas('posts', function ($query) use ($post) {
                $query->published()
                    ->where('region_id', $post->region_id);
            })
            ->withCount(['posts as posts_count' => function ($query) use ($post) {
                $query->published()
                    ->where('region_id', $post->region_id);
            }])
            ->orderByDesc('posts_count')
            ->limit($limit)
            ->get();
    }
}
