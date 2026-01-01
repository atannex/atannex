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
     * Retrieve related posts based on the same category or shared tags.
     *
     * @param  Post  $post  The reference post.
     * @param  int  $limit  Number of posts to retrieve (default: 3).
     * @return Collection<Post>
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
