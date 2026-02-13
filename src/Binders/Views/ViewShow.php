<?php

declare(strict_types=1);

namespace Atannex\Binders\Views;

use App\Enums\HeadingLevel;
use App\Enums\Icon;
use Illuminate\View\View;

trait ViewShow
{
    public const SUPPORTED_PLATFORMS = [
        Icon::FACEBOOK,
        Icon::TWITTER,
        Icon::WHATSAPP,
        Icon::TELEGRAM,
    ];

    public function renderPostShow(string $slug): View
    {
        $module = $this->postService->getPostBySlugPath($slug);

        $post = $module->post;

        $icons = collect(self::SUPPORTED_PLATFORMS)
            ->mapWithKeys(
                fn(string $platform) => [$platform => Icon::getData($platform)]
            )
            ->toArray();

        return view('shows.index', [
            'headingLevels'     => HeadingLevel::asSelectArray(),
            'module'            => $module,
            'post'              => $post,

            'popularTags'       => $this->tagService->popularTagsByPost($post),
            'relatedTags'       => $this->tagService->relatedTagsByPost($post),
            'relatedCategories' => $this->categoryService->relatedCategoriesByPost($post),
            'recentPosts'       => $this->postService->recentPostsByPost($post),
            'relatedPosts'      => $this->postService->relatedPosts($post),

            'navigation'        => $this->getPost->hasPostNavigation($post),
            'medias'            => $this->categoryService->employeeSocial($post->author),
            'icons'             => $icons,
            'seoTitle'          => seo_title($post->title ?? $post->slug),
        ]);
    }
}
