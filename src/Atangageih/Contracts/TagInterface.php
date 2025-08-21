<?php

namespace Atangageih\Contracts;

use App\Models\Tags\Tag;
use Illuminate\Support\Collection;

interface TagInterface
{
    /**
     * Retrieves a tag by its slug.
     *
     * @param string $slug The slug of the tag.
     * @return Tag|null The tag if found, null otherwise.
     */
    public function getTagBySlug(string $slug): ?Tag;

    /**
     * Retrieves all tags associated with a specified blog post.
     *
     * @param int $postId The unique identifier of the blog post.
     * @return Collection A collection of tags associated with the post.
     */
    public function getTagsForPost(int $postId): Collection;

    /**
     * Retrieves all blog posts associated with a tag.
     *
     * @param string $tagId The ID of the tag.
     * @return Collection A collection of posts associated with the tag.
     */
    public function getPostsForTag(string $tagId): Collection;

    /**
     * Retrieves the most popular tags based on the number of associated posts.
     *
     * @param int $limit Number of tags to return.
     * @return Collection A collection of popular tags limited by $limit.
     */
    public function getPopularTags(int $limit = 10): Collection;
}
