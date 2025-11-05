<?php

namespace Atannex\Views;

use App\Models\Tags\Tag;
use Illuminate\View\View;
use App\Models\Regions\Region;
use App\Models\Regions\Category;
use App\Models\Regions\Employee;
use Atannex\Views\Traits\CanRender;
use Atannex\Views\Traits\HasContent;
use Atannex\Views\Traits\HasDate;
use Atannex\Views\Traits\HasShow;

/**
 * Render views for content regions using CategoryService orchestration.
 */
trait Views
{
    use CanRender;
    use HasContent;
    use HasShow;
    use HasDate;

    /**
     * Render tag page with posts and related data.
     */
    public function renderTagView(Tag $tag): View
    {
        $first = $tag->posts->first();

        return $this->renderView('tag', [
            'tag'               => $tag,
            'posts'             => $this->categoryService->postsByTag($tag),
            'relatedCategories' => $this->categoryService->relatedCategoriesByTag($tag),
            'recentPosts'       => $first ? $this->categoryService->recentPosts($first, 6) : collect(),
            'popularTags'       => $this->categoryService->popularTags($tag, 8),
        ], seo_title($tag->name));
    }

    /**
     * Render region page.
     */
    public function renderRegionView(Region $region): View
    {
        return $this->renderView('region', [
            'region' => $region,
            'posts'  => $this->categoryService->postsByRegion($region),
        ], seo_title($region->name));
    }

    /**
     * Render region page by slug.
     */
    public function renderRegionPageView(string $slug): View
    {
        $region = $this->pageService->getMainRegion($slug);

        $this->resolveSection($region);

        return $this->renderView('region-page', [
            'region' => $region
        ], seo_title($region->title));
    }

    /**
     * Render author profile.
     */
    public function renderAuthorView(Employee $author): View
    {
        return $this->renderView('author', [
            'author'      => $author,
            'posts'       => $this->categoryService->postsByAuthor($author->user->slug),
            'user_medias' => $this->categoryService->employeeSocial($author),
        ], seo_title($author->name));
    }

    /**
     * Render category page with related elements.
     */
    public function renderCategoryView(Category $category): View
    {
        $posts = $this->categoryService->postsByCategory($category);
        $first = $posts->first();

        return $this->renderView('category', [
            'category'          => $category,
            'posts'             => $posts,
            'popularTags'       => $first ? $this->categoryService->popularTags($first->tags->first(), 8) : collect(),
            'recentPosts'       => $first ? $this->categoryService->recentPosts($first, 6) : collect(),
            'relatedCategories' => $this->categoryService->relatedCategories($category),
        ], seo_title($category->name));
    }
}
