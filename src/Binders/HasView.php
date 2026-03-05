<?php

declare(strict_types=1);

namespace Atannex\Binders;

use App\Enums\Traits\HasEntityMapping;
use Atannex\Binders\Views\ViewDate;
use Atannex\Binders\Views\ViewRegion;
use Atannex\Binders\Views\ViewShow;
use Atannex\Facades\Atannex;
use Atannex\Services\AuthorService;
use Atannex\Services\CategoryService;
use Atannex\Services\PostService;
use Atannex\Services\RegionService;
use Atannex\Services\ShareService;
use Atannex\Services\TagService;
use Atannex\Traits\Resolution;
use Illuminate\View\View;

final class HasView
{
    public function __construct(
        protected readonly RegionService $regionService,
        protected readonly Atannex $atannex,
        protected readonly TagService $tagService,
        protected readonly CategoryService $categoryService,
        protected readonly ShareService $shareService,
        protected readonly PostService $postService,
        protected readonly AuthorService $authorService,
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Core Resolution & Mapping
    |--------------------------------------------------------------------------
    */
    use HasEntityMapping;
    use Resolution;

    /*
    |--------------------------------------------------------------------------
    | View Composition Traits
    |--------------------------------------------------------------------------
    */
    use ViewDate;
    use ViewRegion;
    use ViewShow;

    /**
     * Render author profile page.
     */
    public function renderAuthorView(string $slug): View
    {
        $author = $this->authorService->resolveAuthorBySlug($slug);

        return view('author', [
            'author' => $author,
            'posts' => $this->authorService->postsByAuthor($author->user->slug),
            'user_medias' => $this->categoryService->employeeSocial($author),
            'seoTitle' => seo_title($author->name),
        ]);
    }

    /**
     * Render category page.
     */
    public function renderCategoryView(string $slug): View
    {
        $category = $this->categoryService->resolveCategoryBySlug($slug);

        return view('category', [
            'category' => $category,
            'posts' => $this->categoryService->postsByCategory($category),
            'recentPosts' => $this->categoryService->recentPostsByCategory($category, 6),
            'relatedCategories' => $this->categoryService->relatedCategories($category),
            'popularTags' => $this->categoryService->popularTagsByCategory($category, 8),
            'seoTitle' => seo_title($category->name),
        ]);
    }

    /**
     * Render tag page.
     */
    public function renderTagView(string $slug): View
    {
        $tag = $this->tagService->getTagBySlug($slug);

        $posts = $this->categoryService->postsByTag($tag);

        $firstPost = $posts->firstOrFail();
        $category = $firstPost->category;

        return view('tag', [
            'tag' => $tag,
            'posts' => $posts,
            'recentPosts' => $this->tagService->getRecentPostsForTag($tag, 6),
            'popularTags' => $this->categoryService->popularTagsByCategory($category, 8),
            'relatedCategories' => $this->categoryService->relatedCategoriesByTag($tag),
            'seoTitle' => seo_title($tag->name),
        ]);
    }
}
