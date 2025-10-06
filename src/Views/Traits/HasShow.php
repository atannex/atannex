<?php

declare(strict_types=1);

namespace Atannex\Views\Traits;

use App\Enums\Icon;
use Illuminate\View\View;
use App\Models\Regions\Category;
use App\Models\Modules\PostModule;

/**
 * Provides functionality to render post views with related data and social media share links.
 */
trait HasShow
{
    /**
     * Render a single post view with its module, related entities, and social media share links.
     */
    public function renderPostShow(Category $category, string $slug): View
    {
        $module = $this->fetchPostModule($slug);
        $post   = $module->post;

        $shareData = $this->prepareShareData($post->title, $post->description, $post->slug_path);

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

    /**
     * Retrieve the post module with its relationships.
     */
    protected function fetchPostModule(string $slug): PostModule
    {
        return PostModule::with([
            'post.author',
            'post.tags',
            'post.category',
        ])
            ->whereHas('post', fn($query) => $query->where('slug_path', $slug))
            ->firstOrFail();
    }

    /**
     * Prepare social media share link data.
     */
    protected function prepareShareData(string $title, string $description, string $slugPath): array
    {
        $postUrl         = route('page.index', ['slug' => $slugPath]);
        $linkedinSummary = mb_substr(strip_tags($description), 0, 150);

        $shares = $this->shareService->getRawShareLinks(
            $postUrl,
            $title,
            Icon::getValues(),
            $linkedinSummary
        );

        return $this->formatShareData($shares);
    }

    /**
     * Convert raw share links into display-ready data.
     */
    protected function formatShareData(array $shares): array
    {
        return collect($shares)
            ->map(fn(string $url, string $platform) => [
                'label' => Icon::getData($platform)['label'],
                'icon'  => Icon::getData($platform)['icon'],
                'color' => Icon::getData($platform)['color'],
                'url'   => $url,
            ])
            ->values()
            ->all();
    }
}
