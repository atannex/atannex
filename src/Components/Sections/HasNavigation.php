<?php

declare(strict_types=1);

namespace Atannex\Components\Sections;

use App\Enums\Flag;
use App\Models\Posts\Post;

trait HasNavigation
{
    /**
     * Get previous and next posts relative to the given post.
     *
     * @return array{previous: ?Post, next: ?Post}
     */
    public function hasPostNavigation(Post $post): array
    {
        return [
            'previous' => $this->hasAdjacentPost($post, 'previous'),
            'next' => $this->hasAdjacentPost($post, 'next'),
        ];
    }

    /**
     * Get adjacent post (previous or next) without caching.
     *
     * @param  string  $direction  Either 'previous' or 'next'
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
