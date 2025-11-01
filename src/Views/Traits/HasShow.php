<?php

declare(strict_types=1);

namespace Atannex\Views\Traits;

use App\Enums\Icon;
use Illuminate\View\View;
use App\Models\Regions\Category;
use App\Models\Modules\PostModule;
use Atannex\Traits\HasPlatforms;

trait HasShow
{
    use HasPlatforms;

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

    protected function buildPostShowData(Category $category, $post, PostModule $module): array
    {
        return [
            'module'            => $module,
            'popularTags'       => $this->tagService->getPopularTags(),
            'relatedTags'       => $this->tagService->getTagsForPost($post->id),
            'navigation'        => $this->getPost->getPostNavigation($post),
            'relatedCategories' => $this->categoryService->getRelatedCategoriesForCategory($category),
            'recentPosts'       => $this->categoryService->getRecentPosts($post),
            'relatedPosts'      => $this->getPost->getRelatedPosts($post),
            'medias'            => $this->categoryService->getPublishedEmployeeSocialMedia($post->author),
            'icons'            => $this->getAllShareIcons(),
        ];
    }

    /**
     * Return all social platform metadata from Icon enum.
     *
     * @return array<int, array{label:string,icon:string,color:string,platform:string}>
     */
    /**
     * Return only supported social platform icon metadata.
     *
     * @return array<string, array{label:string,icon:string,color:string}>
     */
    protected function getAllShareIcons(): array
    {
        return collect(self::SUPPORTED_PLATFORMS)
            ->mapWithKeys(fn(string $platform) => [
                $platform => Icon::getData($platform)
            ])
            ->all();
    }
}
