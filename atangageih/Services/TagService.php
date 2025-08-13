<?php

declare(strict_types=1);

namespace Atangageih\Services;

use App\Models\Tags\Tag;
use Illuminate\Support\Collection;
use Atangageih\Contracts\TagInterface;

/**
 * Service class for managing tag-related operations.
 */
class TagService
{
    /**
     * The repository implementation.
     *
     * @var TagInterface
     */
    protected TagInterface $repository;

    /**
     * Create a new service instance.
     *
     * @param TagInterface $repository The tag repository implementation.
     */
    public function __construct(TagInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Retrieves a tag by its slug.
     *
     * @param string $slug The unique slug of the tag.
     * @return Tag|null The tag model if found, null otherwise.
     */
    public function getTagBySlug(string $slug): ?Tag
    {
        return $this->repository->getTagBySlug($slug);
    }

    /**
     * Retrieves all tags associated with a specific blog post.
     *
     * @param int $postId The unique identifier of the blog post.
     * @return Collection A collection of tags associated with the post.
     */
    public function getTagsForPost(int $postId): Collection
    {
        return $this->repository->getTagsForPost($postId);
    }

    /**
     * Retrieves all blog posts associated with a specific tag.
     *
     * @param string $tagId The unique identifier of the tag.
     * @return Collection A collection of posts associated with the tag.
     */
    public function getPostsForTag(string $tagId): Collection
    {
        return $this->repository->getPostsForTag($tagId);
    }

    /**
     * Retrieves popular tags limited by the specified count.
     *
     * @param int $limit Maximum number of tags to retrieve (default: 10).
     * @return Collection A collection of popular tags.
     */
    public function getPopularTags(int $limit = 10): Collection
    {
        return $this->repository->getPopularTags($limit);
    }
}
