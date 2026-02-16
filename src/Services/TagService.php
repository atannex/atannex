<?php

declare(strict_types=1);

namespace Atannex\Services;

use App\Models\Tags\Tag;
use App\Models\Posts\Post;
use Illuminate\Support\Collection;

class TagService
{
    public function getTagBySlug(string $slug): ?Tag
    {
        return Tag::query()
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function getTagsForPost(int $postId): Collection
    {
        $post = $this->findPost($postId);

        return $post->tags()
            ->orderBy('name')
            ->get();
    }

    public function getPostsForTag(string $tagSlug): Collection
    {
        $tag = $this->getTagBySlug($tagSlug);

        return $tag->posts()
            ->published()
            ->orderByDesc('published_at')
            ->get();
    }

    /**
     * Get latest published posts for a specific tag.
     */
    public function getRecentPostsForTag(Tag $tag, int $limit = 1): Collection
    {
        return $tag->posts()
            ->published()
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    public function searchTags(string $searchTerm): Collection
    {
        return Tag::query()
            ->where('name', 'like', '%' . $searchTerm . '%')
            ->orderBy('name')
            ->get();
    }

    public function relatedTagsByPost(Post $post, int $limit = 8): Collection
    {
        $excludedTagIds = $post->tags->pluck('id');

        return Tag::query()
            ->whereNotIn('id', $excludedTagIds)
            ->whereHas('posts', function ($query) use ($post) {
                $query->published()
                    ->where('category_id', $post->category_id);
            })
            ->withCount(['posts as posts_count' => function ($query) use ($post) {
                $query->published()
                    ->where('category_id', $post->category_id);
            }])
            ->orderByDesc('posts_count')
            ->limit($limit)
            ->get();
    }


    public function popularTagsByPost(Post $post, int $limit = 8): Collection
    {
        return Tag::query()
            ->whereHas('posts', function ($query) use ($post) {
                $query->published()
                    ->where('category_id', $post->category_id);
            })
            ->withCount(['posts as posts_count' => function ($query) use ($post) {
                $query->published()
                    ->where('category_id', $post->category_id);
            }])
            ->orderByDesc('posts_count')
            ->limit($limit)
            ->get();
    }

    public function popularTagsGlobal(int $limit = 10): Collection
    {
        return Tag::query()
            ->whereHas('posts', fn($q) => $q->published())
            ->withCount(['posts as posts_count' => fn($q) => $q->published()])
            ->orderByDesc('posts_count')
            ->limit($limit)
            ->get();
    }

    private function findPost(int $postId): Post
    {
        return Post::query()
            ->published()
            ->findOrFail($postId);
    }
}
