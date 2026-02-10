<?php

declare(strict_types=1);

namespace Atannex\Binders\Views;

use App\Enums\HeadingLevel;
use App\Enums\Icon;
use App\Models\Modules\PostModule;
use App\Models\Regions\Category;
use Illuminate\Database\Eloquent\Builder;
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
        $module = PostModule::query()
            ->with([
                'post' => fn($query) => $query
                    ->withCount('comments')
                    ->with([
                        'author.user',
                        'tags',
                        'category',
                    ]),
            ])
            ->whereHas(
                'post',
                fn(Builder $query) => $query->where('slug_path', $slug)
            )
            ->firstOrFail();

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
