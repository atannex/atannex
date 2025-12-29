<?php

declare(strict_types=1);

namespace Atannex\Views\Traits;

use App\Enums\PostType;
use App\Models\Modules\PostModule;
use App\Models\Regions\Category;
use Atannex\Concerns\HasPlatforms;
use Illuminate\View\View;

/**
 * Trait HasShow
 *
 * Centralized logic for rendering post "show" pages
 * without caching.
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
     */
    protected function fetchPostModule(string $slug): PostModule
    {
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

    /**
     * Build and return all data required by the post detail view.
     */
    protected function buildPostShowData(
        Category $category,
        $post,
        PostModule $module
    ): array {
        return [
            'module' => $module,

            'popularTags' => $this->tagService->getPopularTags(),

            'relatedTags' => $this->tagService->getTagsForPost(
                (int) $post->id
            ),

            'navigation' => $this->getPost->getPostNavigation($post),

            'relatedCategories' => $this->categoryService->relatedCategories(
                $category
            ),

            'recentPosts' => $this->categoryService->recentPosts($post),

            'relatedPosts' => $this->getPost->getRelatedPosts($post),

            'medias' => $this->categoryService->employeeSocial(
                $post->author
            ),

            'icons' => $this->getAllShareIcons(),
        ];
    }
}
