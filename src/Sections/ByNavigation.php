<?php

namespace Atannex\Sections;

use App\Enums\Flag;
use App\Models\Posts\Post;

trait ByNavigation
{
    /**
     * Get previous and next posts relative to the given post.
     *
     * @return array{previous: ?Post, next: ?Post}
     */
    public function getPostNavigation(Post $post): array
    {
        return [
            'previous' => $this->getAdjacentPost($post, 'previous'),
            'next' => $this->getAdjacentPost($post, 'next'),
        ];
    }

    /**
     * Get adjacent post (previous or next) without caching.
     *
     * @param  string  $direction  Either 'previous' or 'next'
     */
    public function getAdjacentPost(Post $post, string $direction): ?Post
    {
        $operator = $direction === 'previous' ? '<' : '>';
        $order = $direction === 'previous' ? 'desc' : 'asc';

        return Post::where('category_id', $post->category_id)
            ->where('id', $operator, $post->id)
            ->flagged(Flag::PUBLISHED)
            ->orderBy('id', $order)
            ->first();
    }
}
