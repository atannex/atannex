<?php

declare(strict_types=1);

namespace Atannex\Views\Traits;

use App\Enums\PostType;
use App\Models\Modules\PostModule;
use App\Models\Regions\Category;
use Atannex\Concerns\HasPlatforms;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

/**
 * Trait HasShow
 *
 * Centralized, cached logic for rendering post "show" pages
 * based on post module type.
 */
trait HasShow
{
    use HasPlatforms;

    /**
     * Render the full post "show" page.
     */
    public function renderPostShow(Category $category, string $slug): View
    {
        $module = $this->fetchPostModule($slug);
        $post   = $module->post;

        return $this->renderView(
            $this->resolvePostShowView($module),
            $this->buildPostShowData($category, $post, $module),
            seo_title($post->title)
        );
    }

    /**
     * Resolve the correct show view based on post type.
     * Article is the default fallback.
     */
    protected function resolvePostShowView(PostModule $module): string
    {
        return match ($module->type->value) {
            PostType::VIDEO => 'shows.video',
            PostType::AUDIO => 'shows.audio',
            default         => 'shows.index',
        };
    }

    /**
     * Retrieve the PostModule instance for the requested slug.
     * Cached to avoid repeated heavy joins.
     */
    protected function fetchPostModule(string $slug): PostModule
    {
        return Cache::remember(
            $this->cacheKey("post_module.slug.{$slug}"),
            now()->addMinutes(20),
            static function () use ($slug): PostModule {
                return PostModule::with([
                    'post.author',
                    'post.tags',
                    'post.category',
                ])
                    ->whereHas(
                        'post',
                        fn($query) => $query->where('slug_path', $slug)
                    )
                    ->firstOrFail();
            }
        );
    }

    /**
     * Build and return all data required by the post detail view.
     * Each expensive dependency is cached independently.
     */
    protected function buildPostShowData(
        Category $category,
        $post,
        PostModule $module
    ): array {
        $postId     = (int) $post->id;
        $categoryId = (int) $category->id;
        $authorId   = (int) $post->author->id;

        return [
            'module' => $module,

            'popularTags' => Cache::remember(
                $this->cacheKey('tags.popular'),
                now()->addMinutes(45),
                fn() => $this->tagService->getPopularTags()
            ),

            'relatedTags' => Cache::remember(
                $this->cacheKey("tags.post.{$postId}"),
                now()->addMinutes(20),
                fn() => $this->tagService->getTagsForPost($postId)
            ),

            'navigation' => Cache::remember(
                $this->cacheKey("post.navigation.{$postId}"),
                now()->addMinutes(15),
                fn() => $this->getPost->getPostNavigation($post)
            ),

            'relatedCategories' => Cache::remember(
                $this->cacheKey("categories.related.{$categoryId}"),
                now()->addMinutes(60),
                fn() => $this->categoryService->relatedCategories($category)
            ),

            'recentPosts' => Cache::remember(
                $this->cacheKey("posts.recent.{$postId}"),
                now()->addMinutes(10),
                fn() => $this->categoryService->recentPosts($post)
            ),

            'relatedPosts' => Cache::remember(
                $this->cacheKey("posts.related.{$postId}"),
                now()->addMinutes(15),
                fn() => $this->getPost->getRelatedPosts($post)
            ),

            'medias' => Cache::remember(
                $this->cacheKey("author.media.{$authorId}"),
                now()->addMinutes(60),
                fn() => $this->categoryService->employeeSocial($post->author)
            ),

            'icons' => $this->getAllShareIcons(),
        ];
    }

    /**
     * Generate consistent cache keys.
     * Centralized to allow easy versioning.
     */
    protected function cacheKey(string $key): string
    {
        return "view.show.v1.{$key}";
    }
}
