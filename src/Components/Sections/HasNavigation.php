<?php

declare(strict_types=1);

namespace Atannex\Components\Sections;

use App\Enums\Flag;
use App\Models\Posts\Post;

trait HasNavigation
{
    /**
     * Provide the adjacent posts (previous and next) for a given post within the same category.
     *
     * @param Post $post The reference post used to locate adjacent posts in the same category.
     * @return array{previous: ?Post, next: ?Post} `previous` is the nearest published post with an ID less than the given post's ID, `next` is the nearest published post with an ID greater than the given post's ID; each is `null` if none exists.
     */
    public function hasPostNavigation(Post $post): array
    {
        return [
            'previous' => $this->hasAdjacentPost($post, 'previous'),
            'next' => $this->hasAdjacentPost($post, 'next'),
        ];
    }

    /**
     * Find the previous or next published post within the same category as the given post.
     *
     * @param string $direction Either 'previous' to find the preceding post or 'next' to find the succeeding post.
     * @return Post|null The adjacent published Post in the specified direction, or null if none exists.
     */
    public function hasAdjacentPost(Post $post, string $direction): ?Post
    {
        $operator = $direction === 'previous' ? '<' : '>';
        $order = $direction === 'previous' ? 'desc' : 'asc';

        return Post::where('category_id', $post->category_id)
            ->where('id', $operator, $post->id)
            ->published()
            ->orderBy('id', $order)
            ->first();
    }
}