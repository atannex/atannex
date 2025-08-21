<?php

namespace Atangageih\Repositories;

use App\Enums\Flag;
use App\Models\Tags\Tag;
use App\Models\Posts\Post;
use Illuminate\Support\Collection;

class TagRepository implements \Atangageih\Contracts\TagInterface
{
    /**
     * Retrieves a tag by its slug.
     *
     * @param string $slug
     * @return Tag|null
     */
    public function getTagBySlug(string $slug): ?Tag
    {
        return Tag::where('slug', $slug)->first();
    }

    /**
     * Retrieves all tags associated with a specified blog post, ordered alphabetically by name.
     *
     * @param int $postId
     * @return Collection<Tag>
     */
    public function getTagsForPost(int $postId): Collection
    {
        $post = Post::findOrFail($postId);
        return $post->tags()->orderBy('name')->get();
    }

    /**
     * Retrieves all blog posts associated with a tag, ordered by published date descending.
     *
     * @param string $tagId
     * @return Collection<Post>
     */
    public function getPostsForTag(string $tagId): Collection
    {
        $tag = Tag::findOrFail($tagId);
        return $tag->posts()->orderBy('published_at', 'desc')->get();
    }

    /**
     * Searches tags by partial name match, ordered alphabetically by name.
     *
     * @param string $searchTerm
     * @return Collection<Tag>
     */
    public function searchTags(string $searchTerm): Collection
    {
        return Tag::where('name', 'like', '%' . $searchTerm . '%')
            ->orderBy('name')
            ->get();
    }

    /**
     * Retrieves the most popular tags based on the number of associated published posts,
     * including pivot data such as 'slug_path' from the post_tag pivot table.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection<Tag>
     */
    public function getPopularTags(int $limit = 10): Collection
    {
        return Tag::whereHas('posts', function ($query) {
            $query->where('flag', Flag::PUBLISHED);
        })
            ->withCount(['posts' => function ($query) {
                $query->where('flag', Flag::PUBLISHED);
            }])
            ->orderByDesc('posts_count')
            ->with(['posts' => function ($query) {
                $query->where('flag', Flag::PUBLISHED)
                    ->with('category')
                    ->select('posts.*');
            }])
            ->take($limit)
            ->get();
    }
}
