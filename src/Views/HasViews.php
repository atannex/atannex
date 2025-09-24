<?php

namespace Atannex\Views;

use App\Models\Tags\Tag;
use Illuminate\View\View;
use App\Models\Pivots\PostTag;
use App\Models\Regions\Region;
use App\Models\Regions\Category;
use App\Models\Regions\Employee;
use App\Models\Modules\PostModule;
use Atannex\Views\Traits\CanRender;
use Atannex\Views\Traits\HasContent;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Trait HasViews
 * Provides view rendering methods for various region types
 */
trait HasViews
{
    use CanRender;
    use HasContent;

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
     * Render a single post view with its module and author social media.
     *
     * @param Category $category
     * @param string $slug
     * @return View
     */
    public function renderPostShow(Category $category, string $slug): View
    {
        $module = PostModule::with('post')
            ->whereHas('post', fn($q) => $q->where('slug_path', $slug))
            ->firstOrFail();

        $post = $module->post;

        $viewData = [
            'module'            => $module,
            'popularTags'       => $this->tagService->getPopularTags(),
            'relatedTags'       => $this->tagService->getTagsForPost($post->id),
            'navigation'        => $this->getPost->getPostNavigation($post),
            'relatedCategories' => $this->categoryService->getRelatedCategoriesForCategory($category),
            'recentPosts'       => $this->categoryService->getRecentPosts($post),
            'relatedPosts'      => $this->getPost->getRelatedPosts($post),
            'medias'            => $this->categoryService->getPublishedEmployeeSocialMedia($post->author),
        ];

        return $this->renderView('shows.index', $viewData, seo_title($post->title));
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

    /**
     * Render posts filtered by date.
     *
     * @param string $value
     * @param string $type
     * @param string|null $year
     * @return View
     */
    public function renderDateView(string $value, string $type, ?string $year = null): View
    {
        $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ];

        $isMonth = $type === 'month';
        $displayValue = $isMonth ? ($months[(int)$value] ?? $value) : $value;
        $yearMonth = $isMonth ? ($year ?? date('Y')) . '/' . $value : $value;

        $seoTitle = $isMonth
            ? 'Posts for the month of ' . $displayValue
            : 'Posts for the year ' . $value;

        return $this->renderView('date', [
            'posts' => $this->categoryService->getPostsByDate($yearMonth),
        ], seo_title($seoTitle));
    }
}
