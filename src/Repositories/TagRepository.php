<?php

namespace Atannex\Repositories;

use App\Models\Tags\Tag;
use App\Models\Posts\Post;
use Illuminate\Support\Collection;
use Atannex\Contracts\TagInterface;

class TagRepository implements TagInterface
{
    /**
     * Retrieves a tag by its slug (from the tags table).
     */
    public function getTagBySlug(string $slug): ?Tag
    {
        return $this->queryTag()
            ->where('slug', $slug)
            ->first();
    }

    /**
     * Retrieves all tags associated with a specified blog post,
     * ordered alphabetically by name.
     *
     * @return Collection<Tag>
     */
    public function getTagsForPost(int $postId): Collection
    {
        $post = $this->findPost($postId);

        return $post->tags()
            ->orderBy('name')
            ->get();
    }

    /**
     * Retrieve all posts tagged with the given tag slug, ordered by `published_at` descending.
     *
     * @param string $tagSlug The slug identifying the tag.
     * @return \Illuminate\Support\Collection|array<int,\App\Models\Post> A collection of Post models ordered by `published_at` descending.
     */
    public function getPostsForTag(string $tagSlug): Collection
    {
        $tag = $this->getTagBySlug($tagSlug);

        return $tag->posts()
            ->published()
            ->orderByDesc('published_at')
            ->get();
    }

    /**
     * Searches tags by partial name match, ordered alphabetically by name.
     *
     * @return Collection<Tag>
     */
    public function searchTags(string $searchTerm): Collection
    {
        return $this->queryTag()
            ->where('name', 'like', '%' . $searchTerm . '%')
            ->orderBy('name')
            ->get();
    }

    /**
     * Retrieve the most popular tags by number of published posts.
     *
     * @param int $limit The maximum number of tags to return.
     * @return Collection<Tag> Collection of Tag models that have at least 2 published posts, ordered by published posts count descending.
     */
    public function getPopularTags(int $limit = 10): Collection
    {
        return Tag::withCount([
            'posts' => fn($q) => $q->published(),
        ])
            ->having('posts_count', '>=', 2)
            ->orderByDesc('posts_count')
            ->take($limit)
            ->get();
    }

    /**
     * Reusable base query for Tag model.
     */
    private function queryTag()
    {
        return Tag::query();
    }

    /**
     * Retrieve a published Post by its ID or throw an exception if none exists.
     *
     * @param int $postId The ID of the post to find (must be published).
     * @return Post The found Post model.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If no published post with the given ID exists.
     */
    private function findPost(int $postId): Post
    {
        return Post::query()
            ->published()
            ->findOrFail($postId);
    }
}