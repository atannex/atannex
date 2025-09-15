<?php

namespace Atannex\Components\GetPosts;

use App\Models\Tags\Tag;
use Illuminate\Support\Collection;
use Atannex\Traits\HasPostsForHierarchy;

trait ByTag
{
    use HasPostsForHierarchy;

    public function getPostsByTag(array $config = []): Collection
    {
        $tagIds = (array) ($config['tag_id']);

        return $this->fetchPostsForHierarchy(
            $tagIds,
            $config,
            Tag::class,
            'tags',
            null,
            fn($tag) => [$tag->id],
            fn($post) => $post->tags->first()->id
        );
    }
}
