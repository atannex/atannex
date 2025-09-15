<?php

namespace Atannex\Repositories;

use App\Models\Tags\Tag;
use App\Models\Posts\Post;
use Illuminate\Support\Collection;
use Atannex\Contracts\TagInterface;

class TagRepository implements TagInterface
{
    /**
     * Retrieves a tag by its slug.
     */
    public function getTagBySlug(string $slug): ?Tag
    {
        return $this->queryTag()->where('slug', $slug)->first();
    }

    /**
     * Retrieves all tags associated with a specified blog post, ordered alphabetically by name.
     *
     * @return Collection<Tag>
     */
    public function getTagsForPost(int $postId): Collection
    {
        $post = $this->findPost($postId);
        return $post->tags()->orderBy('name')->get();
    }

    /**
     * Retrieves all blog posts associated with a tag, ordered by published date descending.
     *
     * @return Collection<Post>
     */
    public function getPostsForTag(string $tagId): Collection
    {
        $tag = $this->findTag($tagId);
        return $tag->posts()->orderByDesc('published_at')->get();
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
     * Retrieves the most popular tags based on the number of associated published posts.
     *
     * @param int $limit
     * @return Collection<Tag>
     */
    public function getPopularTags(int $limit = 10): Collection
    {
        return $this->queryTag()
            ->whereHas('posts', fn($q) => $q->published())
            ->withCount(['posts' => fn($q) => $q->published()])
            ->orderByDesc('posts_count')
            ->with(['posts' => fn($q) => $q->published()->with('category')->select('posts.*', 'post_tag.slug_path')])
            ->having('posts_count', '>=', 2)
            ->take($limit)
            ->get();
    }

    /**
     * Reusable base query for Tag.
     */
    private function queryTag()
    {
        return Tag::query();
    }

    /**
     * Finds a tag or fails.
     */
    private function findTag(string $tagId): Tag
    {
        return Tag::findOrFail($tagId);
    }

    /**
     * Finds a post or fails.
     */
    private function findPost(int $postId): Post
    {
        return Post::findOrFail($postId);
    }
}
