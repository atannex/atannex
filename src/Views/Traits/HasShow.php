<?php

declare(strict_types=1);

namespace Atannex\Views\Traits;

use App\Enums\Icon;
use Illuminate\View\View;
use App\Models\Regions\Category;
use App\Models\Modules\PostModule;

/**
 * Trait for rendering post views with related data and social media share links.
 */
trait HasShow
{
    /**
     * Renders a single post view with its module, related data, and social media share links.
     *
     * @param Category $category The category of the post.
     * @param string $slug The slug of the post.
     * @return View The rendered view.
     */
    public function renderPostShow(Category $category, string $slug): View
    {
        $module = PostModule::with('post')
            ->whereHas('post', fn($q) => $q->where('slug_path', $slug))
            ->firstOrFail();

        $post = $module->post;
        $postUrl = route('page.index', ['slug' => $post->slug_path]);

        $linkedinSummary = substr(strip_tags($post->description), 0, 150);
        $shares = $this->shareService->getRawShareLinks(
            $postUrl,
            $post->title,
            array_keys(Icon::$data),
            $linkedinSummary
        );

        $shareData = collect($shares)
            ->map(fn($url, $platform) => array_merge(Icon::getData(strtolower($platform)), ['url' => $url]))
            ->values()
            ->all();

        return $this->renderView('shows.index', [
            'module'            => $module,
            'popularTags'       => $this->tagService->getPopularTags(),
            'relatedTags'       => $this->tagService->getTagsForPost($post->id),
            'navigation'        => $this->getPost->getPostNavigation($post),
            'relatedCategories' => $this->categoryService->getRelatedCategoriesForCategory($category),
            'recentPosts'       => $this->categoryService->getRecentPosts($post),
            'relatedPosts'      => $this->getPost->getRelatedPosts($post),
            'medias'            => $this->categoryService->getPublishedEmployeeSocialMedia($post->author),
            'shares'            => $shareData,
        ], seo_title($post->title));
    }
}
