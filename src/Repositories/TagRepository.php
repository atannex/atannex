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
     * Retrieves all blog posts associated with a tag (using the tag's slug),
     * ordered by published date descending.
     *
     * @return Collection<Post>
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
     * Retrieves the most popular tags based on the number
     * of associated published posts.
     *
     * @return Collection<Tag>
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
     * Finds a published post by ID or fails.
     */
    private function findPost(int $postId): Post
    {
        return Post::query()
            ->published()
            ->findOrFail($postId);
    }
}
