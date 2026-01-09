<?php

declare(strict_types=1);

namespace Atannex\Components\Sections;

use App\Enums\Flag;
use App\Models\Posts\Post;
use Illuminate\Support\Collection;

/**
 * Trait GetRelatedPost
 *
 * Provides functionality to retrieve related published posts
 * based on category and shared tags.
 */
trait HasRelated
{
    /**
     * Get posts related to the given post by category or shared tags.
     *
     * Returns up to the specified limit of published posts (excluding the reference post) that share the same category or at least one tag.
     *
     * @param Post $post The reference post to find related posts for.
     * @param int $limit Maximum number of related posts to return.
     * @return Collection<Post> A collection of related Post models.
     */
    public function hasRelatedPosts(Post $post, int $limit = 3): Collection
    {
        $tagIds = $post->tags()->pluck('tags.id')->toArray();

        return Post::query()
            ->published()
            ->where('id', '!=', $post->id)
            ->where(function ($query) use ($post, $tagIds) {
                $query->where('category_id', $post->category_id);

                if (! empty($tagIds)) {
                    $query->orWhereHas('tags', function ($q) use ($tagIds) {
                        $q->whereIn('tags.id', $tagIds);
                    });
                }
            })
            ->latest()
            ->limit($limit)
            ->get();
    }
}