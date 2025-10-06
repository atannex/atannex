<?php

namespace Atannex\Views;

use App\Models\Tags\Tag;
use Illuminate\View\View;
use App\Models\Pivots\PostTag;
use App\Models\Regions\Region;
use App\Models\Regions\Category;
use App\Models\Regions\Employee;
use Atannex\Views\Traits\CanRender;
use Atannex\Views\Traits\HasContent;
use Atannex\Views\Traits\HasDate;
use Atannex\Views\Traits\HasShow;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Trait HasViews
 * Provides view rendering methods for various region types
 */
trait HasViews
{
    use CanRender;
    use HasContent;
    use HasShow;
    use HasDate;

    /**
     * Render a tag view with related posts and metadata.
     *
     * @param PostTag $postTag
     * @return View
     * @throws ModelNotFoundException
     */
    public function renderTagView(Tag $tag): View
    {
        $first = $tag->posts->first();

        return $this->renderView('tag', [
            'tag'               => $tag,
            'posts'             => $this->categoryService->getPostsByTag($tag),
            'relatedCategories' => $this->categoryService->getRelatedCategoriesForTag($tag),
            'recentPosts'       => $this->categoryService->getRecentPosts($first, 6),
            'popularTags'       => $this->tagService->getPopularTags(8),
        ], seo_title($tag->name));
    }

    /**
     * Render a region view with associated posts.
     *
     * @param Region $region
     * @return View
     */
    public function renderRegionView(Region $region): View
    {
        return $this->renderView('region', [
            'region' => $region,
            'posts'  => $this->categoryService->getPostsByRegion($region),
        ], seo_title($region->name));
    }

    /**
     * Render a region page.
     *
     * @param string $slug
     * @return View
     */
    public function renderRegionPageView(string $slug): View
    {
        $region = $this->pageService->getMainRegion($slug);

        $this->resolveSection($region);

        return $this->renderView('region-page', ['region' => $region], seo_title($region->title));
    }

    /**
     * Render an author profile view with their posts and social media.
     *
     * @param Employee $author
     * @return View
     */
    public function renderAuthorView(Employee $author): View
    {
        return $this->renderView('author', [
            'author'      => $author,
            'posts'       => $this->categoryService->getPostsByAuthor($author->user->slug),
            'user_medias' => $this->categoryService->getPublishedEmployeeSocialMedia($author),
        ], seo_title($author->name));
    }

    /**
     * Render a category view with its posts.
     *
     * @param Category $category
     * @return View
     */
    public function renderCategoryView(Category $category): View
    {
        $posts = $this->categoryService->getPostsByCategory($category);
        $firstPost = $posts->first();

        return $this->renderView('category', [
            'category'          => $category,
            'posts'             => $posts,
            'popularTags'       => $this->tagService->getPopularTags(),
            'recentPosts'       => $this->categoryService->getRecentPosts($firstPost, 6),
            'relatedCategories' => $this->categoryService->getRelatedCategoriesForCategory($category),
        ], seo_title($category->name));
    }
}
