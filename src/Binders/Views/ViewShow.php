<?php

declare(strict_types=1);

namespace Atannex\Binders\Views;

use App\Enums\HeadingLevel;
use App\Enums\Icon;
use App\Models\Modules\PostModule;
use App\Models\Regions\Category;
use Illuminate\View\View;

trait ViewShow
{
    public const SUPPORTED_PLATFORMS = [
        Icon::FACEBOOK,
        Icon::TWITTER,
        Icon::WHATSAPP,
        Icon::TELEGRAM,
    ];

    public function renderPostShow(Category $category, string $slug): View
    {
        $module = PostModule::with([
            'post' => fn($q) => $q
                ->withCount('comments')
                ->with(['author.user', 'tags', 'category']),
        ])
            ->whereHas('post', fn($q) => $q->where('slug_path', $slug))
            ->firstOrFail();

        $post = $module->post;

        $icons = collect(self::SUPPORTED_PLATFORMS)
            ->mapWithKeys(fn(string $platform) => [$platform => Icon::getData($platform)])
            ->all();

        return view('shows.index', [
            'headingLevels'     => HeadingLevel::asSelectArray(),
            'module'            => $module,
            'post'              => $post,
            'popularTags'       => $this->tagService->getPopularTags(),
            'relatedTags'       => $this->tagService->getTagsForPost($post->id),
            'navigation'        => $this->getPost->hasPostNavigation($post),
            'relatedCategories' => $this->categoryService->relatedCategories($category),
            'recentPosts'       => $this->categoryService->recentPosts($post),
            'relatedPosts'      => $this->getPost->hasRelatedPosts($post),
            'medias'            => $this->categoryService->employeeSocial($post->author),
            'icons'             => $icons,
            'seoTitle'          => seo_title($post->title),
        ]);
    }
}
