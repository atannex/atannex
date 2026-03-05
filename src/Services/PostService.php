<?php

declare(strict_types=1);

namespace Atannex\Services;

use App\Enums\Flag;
use App\Models\Modules\PostModule;
use App\Models\Posts\Post;
use App\Models\Posts\Video;
use App\Models\Regions\Category;
use App\Models\Regions\Region;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;

final class PostService
{
    /*
    |--------------------------------------------------------------------------
    | Recent Posts
    |--------------------------------------------------------------------------
    */

    public function getRecentPosts(int $limit = 5): Collection
    {
        return $this->basePostQuery()
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    public function getRecentPostsByPost(Post $post, int $limit = 6): Collection
    {
        return $this->basePostQuery()
            ->where('category_id', $post->category_id)
            ->whereKeyNot($post->getKey())
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Breaking / Popular / Weekly
    |--------------------------------------------------------------------------
    */

    public function getLatestBreakingPosts(int $limit = 10): Collection
    {
        return Post::breaking()
            ->published()
            ->latest('breaking_at')
            ->limit($limit)
            ->get();
    }

    public function getPopularPosts(int $limit = 5): Collection
    {
        return Post::query()
            ->popular($limit)
            ->get();
    }

    public function getMostReadPosts(int $limit = 5): Collection
    {
        return $this->basePostQuery()
            ->limit($limit)
            ->get();
    }

    public function getPastWeekPosts(int $limit = 5): Collection
    {
        $start = Date::now()->subDays(6)->startOfDay();
        $end = Date::now()->endOfDay();

        return $this->basePostQuery()
            ->whereBetween('published_at', [$start, $end])
            ->withCount('comments')
            ->orderByDesc('comments_count')
            ->limit($limit)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Editor Picks
    |--------------------------------------------------------------------------
    */

    public function getEditorPicks(int $limit = 5): Collection
    {
        return $this->basePostQuery()
            ->activeEditorPick()
            ->orderByDesc('editor_pick_at')
            ->limit($limit)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Related Posts
    |--------------------------------------------------------------------------
    */

    public function getRelatedPosts(Post $post, int $limit = 6): Collection
    {
        $tagIds = $post->tags->pluck('id');

        return $this->basePostQuery()
            ->whereKeyNot($post->getKey())
            ->where('category_id', $post->category_id)
            ->when($tagIds->isNotEmpty(), function ($query) use ($tagIds) {
                $query->whereHas('tags', fn($q) => $q->whereIn('tags.id', $tagIds));
            })
            ->withCount([
                'tags as shared_tags_count' => fn($q) => $q->whereIn('tags.id', $tagIds)
            ])
            ->orderByDesc('shared_tags_count')
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    public function getSimpleRelatedPosts(Post $post, int $limit = 3): Collection
    {
        $tagIds = $post->tags()->pluck('tags.id')->toArray();

        return $this->basePostQuery()
            ->where('id', '!=', $post->id)
            ->where(function ($query) use ($post, $tagIds) {
                $query->where('category_id', $post->category_id);

                if (!empty($tagIds)) {
                    $query->orWhereHas('tags', fn($q) => $q->whereIn('tags.id', $tagIds));
                }
            })
            ->latest()
            ->limit($limit)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Region Posts
    |--------------------------------------------------------------------------
    */

    public function getRegionsWithPosts(int $limit = 5): Collection
    {
        return Region::query()
            ->with('children')
            ->whereNull('parent_id')
            ->get()
            ->map(fn(Region $region) => $this->attachPostsToRegion($region, $limit));
    }

    private function attachPostsToRegion(Region $region, int $limit): Region
    {
        $regionIds = $region->getSelfAndDescendantIds();

        $region->allPosts = Region::query()
            ->whereIn('id', $regionIds)
            ->with([
                'posts' => fn($query) =>
                $query->published()
                    ->latest('published_at')
                    ->limit($limit)
            ])
            ->get()
            ->pluck('posts')
            ->flatten();

        return $region;
    }

    /*
    |--------------------------------------------------------------------------
    | Category Posts
    |--------------------------------------------------------------------------
    */

    public function getCategoriesWithPostsByName(string $name, int $limit = 5): Collection
    {
        return Category::query()
            ->where('name', $name)
            ->get()
            ->map(fn(Category $category) => $this->attachPostsToCategory($category, $limit));
    }

    private function attachPostsToCategory(Category $category, int $limit): Category
    {
        $categoryIds = $category->getDescendants()
            ->pluck('id')
            ->push($category->id);

        $category->allPosts = $this->basePostQuery()
            ->whereIn('category_id', $categoryIds)
            ->latest('published_at')
            ->limit($limit)
            ->get();

        return $category;
    }

    /*
    |--------------------------------------------------------------------------
    | Videos
    |--------------------------------------------------------------------------
    */

    public function getLatestPublishedVideos(int $limit = 6): Collection
    {
        return Video::query()
            ->with([
                'post' => fn($q) => $q
                    ->select('id', 'title', 'slug', 'slug_path', 'published_at', 'category_id', 'author_id')
                    ->published(),
                'post.category:id,name,slug_path',
                'post.author:id,user_id',
                'post.author.user:id,name,slug',
            ])
            ->where('flag', Flag::PUBLISHED)
            ->published()
            ->whereHas('post', fn($q) => $q->published())
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    */

    public function getPostNavigation(Post $post): array
    {
        return [
            'previous' => $this->getAdjacentPost($post, 'previous'),
            'next' => $this->getAdjacentPost($post, 'next'),
        ];
    }

    public function getAdjacentPost(Post $post, string $direction): ?Post
    {
        $operator = $direction === 'previous' ? '<' : '>';
        $order = $direction === 'previous' ? 'desc' : 'asc';

        return $this->basePostQuery()
            ->where('category_id', $post->category_id)
            ->where('id', $operator, $post->id)
            ->orderBy('id', $order)
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Post Retrieval
    |--------------------------------------------------------------------------
    */

    public function getPostBySlugPath(string $slug): PostModule
    {
        return PostModule::query()
            ->whereHas('post', fn($q) => $q->where('slug_path', $slug))
            ->with([
                'post' => fn($query) => $query
                    ->withCount('comments')
                    ->with(['author.user', 'tags', 'category'])
            ])
            ->firstOrFail();
    }

    public function getModulePostBySlug(string $slug): PostModule
    {
        return PostModule::with([
            'post.category',
            'post.author',
            'post.tags',
        ])
            ->whereHas('post', function ($query) use ($slug) {
                $query->where('slug', $slug)
                    ->published()
                    ->flagged(Flag::PUBLISHED);
            })
            ->firstOrFail();
    }

    /*
    |--------------------------------------------------------------------------
    | Base Queries
    |--------------------------------------------------------------------------
    */

    private function basePostQuery()
    {
        return Post::query()->published();
    }
}
