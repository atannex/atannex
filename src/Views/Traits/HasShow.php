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
 * Provides reusable logic for rendering post detail (“show”) pages.
 * This trait centralizes module fetching, SEO setup, relationship
 * loading, and view data construction for category-based content pages.
 */
trait HasShow
{
    use HasPlatforms;

    /**
     * Render the full post "show" page.
     *
     * Responsibilities:
     *  - Resolve a post module by its slug, including all needed relations.
     *  - Build an organized dataset containing tags, navigation, and related posts.
     *  - Pass SEO title and metadata into the base rendering engine.
     *
     * @param  Category  $category  Category context for the post.
     * @param  string    $slug      Post slug (slug_path).
     * @return View
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
     * Retrieve the PostModule instance for the requested slug.
     *
     * Eager-loads all required relationships to ensure efficient rendering:
     *  - Author details
     *  - Post tags
     *  - Category information
     *
     * Uses a slug-path constraint to guarantee unique resolution.
     *
     * @param  string  $slug
     * @return PostModule
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
     * Build and return all data required by the post detail view.
     *
     * Assembles a structured dataset including:
     *  - Tag metadata (popular and related)
     *  - Previous/next navigation
     *  - Related categories and posts
     *  - Author social/employee media
     *  - Share icons for social media integration
     *
     * @param  Category    $category
     * @param  mixed       $post      Post model instance
     * @param  PostModule  $module
     * @return array
     */
    protected function buildPostShowData(Category $category, $post, PostModule $module): array
    {
        return [
            'module'             => $module,
            'popularTags'        => $this->tagService->getPopularTags(),
            'relatedTags'        => $this->tagService->getTagsForPost($post->id),
            'navigation'         => $this->getPost->getPostNavigation($post),
            'relatedCategories'  => $this->categoryService->relatedCategories($category),
            'recentPosts'        => $this->categoryService->recentPosts($post),
            'relatedPosts'       => $this->getPost->getRelatedPosts($post),
            'medias'             => $this->categoryService->employeeSocial($post->author),
            'icons'              => $this->getAllShareIcons(),
        ];
    }
}
