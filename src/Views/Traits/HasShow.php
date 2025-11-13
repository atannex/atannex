<?php

declare(strict_types=1);

namespace Atannex\Views\Traits;

use App\Models\Modules\PostModule;
use App\Models\Regions\Category;
use Atannex\Concerns\HasPlatforms;
use Illuminate\View\View;

/**
 * Trait HasShow
 *
 * Provides reusable logic for rendering post "show" pages.
 * This trait integrates module retrieval, SEO configuration,
 * and view data composition for category-based post pages.
 */
trait HasShow
{
    use HasPlatforms;

    /**
     * Render a full post show page view.
     *
     * @param  Category  $category  The category context for the post.
     * @param  string  $slug  The unique post slug (slug_path).
     * @return View
     *
     * Fetches the PostModule with all required relationships,
     * prepares the view data, and returns the rendered page.
     */
    public function renderPostShow(Category $category, string $slug): View
    {
        $module = $this->fetchPostModule($slug);
        $post = $module->post;

        return $this->renderView(
            'shows.index',
            $this->buildPostShowData($category, $post, $module),
            seo_title($post->title)
        );
    }

    /**
     * Retrieve the PostModule and eager-load dependencies.
     *
     * @param  string  $slug  The slug path of the target post.
     * @return PostModule
     *
     * Loads the post module with author, tags, and category relationships.
     * Uses slug-based resolution to ensure unique retrieval.
     */
    protected function fetchPostModule(string $slug): PostModule
    {
        return PostModule::with([
            'post.author',
            'post.tags',
            'post.category',
        ])
            ->whereHas('post', fn ($query) => $query->where('slug_path', $slug))
            ->firstOrFail();
    }

    /**
     * Construct all necessary data for the post view.
     *
     * @param  Category  $category  The category context.
     * @param  mixed  $post  The post model instance.
     * @param  PostModule  $module  The loaded PostModule.
     * @return array
     *
     * Collects all related resources including tags, navigation,
     * related content, social media links, and sharing icons.
     */
    protected function buildPostShowData(Category $category, $post, PostModule $module): array
    {
        return [
            'module' => $module,
            'popularTags' => $this->tagService->getPopularTags(),
            'relatedTags' => $this->tagService->getTagsForPost($post->id),
            'navigation' => $this->getPost->getPostNavigation($post),
            'relatedCategories' => $this->categoryService->relatedCategories($category),
            'recentPosts' => $this->categoryService->recentPosts($post),
            'relatedPosts' => $this->getPost->getRelatedPosts($post),
            'medias' => $this->categoryService->employeeSocial($post->author),
            'icons' => $this->getAllShareIcons(),
        ];
    }
}
