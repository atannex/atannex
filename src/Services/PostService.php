<?php

declare(strict_types=1);

namespace Atannex\Services;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;


final class PostService
{
    public function recentPostsByPost(Post $post, int $limit = 6): Collection
    {
        return Post::query()
            ->published()
            ->where('category_id', $post->category_id)
            ->whereKeyNot($post->getKey())
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    public function relatedPosts(Post $post, int $limit = 6): Collection
    {
        $tagIds = $post->tags->pluck('id');

        return Post::query()
            ->published()
            ->whereKeyNot($post->getKey())
            ->where('category_id', $post->category_id)
            ->when($tagIds->isNotEmpty(), function ($query) use ($tagIds) {
                $query->whereHas('tags', function ($q) use ($tagIds) {
                    $q->whereIn('tags.id', $tagIds);
                });
            })
            ->withCount(['tags as shared_tags_count' => function ($q) use ($tagIds) {
                $q->whereIn('tags.id', $tagIds);
            }])
            ->orderByDesc('shared_tags_count')
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }
}
