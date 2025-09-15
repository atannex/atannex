<?php

namespace Atannex\Views;

use Illuminate\View\View;
use App\Models\Posts\Post;
use App\Models\Pages\Category;
use App\Models\Pivots\PostTag;
use App\Models\Regions\Region;
use App\Models\Regions\Employee;
use App\Models\Modules\PostModule;
use Atannex\Views\Traits\CanRender;
use Atannex\Views\Traits\HasContent;

trait HasViews
{
    use CanRender;
    use HasContent;

    /**
     * Render a tag page with related posts and metadata.
     */
    public function renderTagView(PostTag $postTag): View
    {
        $tag   = $postTag->tag;
        $first = $tag->posts->first();

        return $this->renderView('tag', [
            'tag'               => $tag,
            'posts'             => $this->categoryService->getPostsByTag($tag),
            'relatedCategories' => $this->categoryService->getRelatedCategoriesForTag($tag),
            'recentPosts'       => $first ? $this->categoryService->getRecentPosts($first, 6) : collect(),
            'popularTags'       => $this->tagService->getPopularTags(8),
        ], seo_title($tag->name));
    }

    /**
     * Render a region page with associated posts.
     */
    public function renderRegionView(Region $region): View
    {
        return $this->renderView('region', [
            'region' => $region,
            'posts'  => $this->categoryService->getPostsByRegion($region)
        ], seo_title($region->name));
    }

    /**
     * Render a single post page with its module and author social media.
     */
    public function renderPostShow(Category $category, string $slug): View
    {
        $post   = Post::where('slug_path', $slug)->firstOrFail();
        $module = PostModule::with('post')
            ->whereHas('post', fn($q) => $q->where('slug_path', $slug))
            ->firstOrFail();

        return $this->renderView(
            'shows.index',
            [
                'module' => $module,
                'medias' => $module->post
                    ? $this->categoryService->getPublishedEmployeeSocialMedia($module->post->author)
                    : collect(),
            ],
            seo_title($post->title),
            $category,
            $post
        );
    }

    /**
     * Render a static page.
     */
    public function renderPageView(string $slug): View
    {
        $page = $this->pageService->getHomePage($slug);
        $this->resolveSection($page);

        return $this->renderView('pages', ['page' => $page], seo_title($page->title));
    }

    /**
     * Render an author profile page with their posts and social media.
     */
    public function renderAuthorView(Employee $author): View
    {
        return $this->renderView('author', [
            'author'      => $author,
            'posts'       => $this->categoryService->getPostsByAuthor($author->user->slug),
            'user_medias' => $this->categoryService->getPublishedEmployeeSocialMedia($author),
        ], seo_title($author->name ?? 'Author'));
    }

    /**
     * Render a category page with its posts.
     */
    public function renderCategoryView(Category $category): View
    {
        $posts = $this->categoryService->getPostsByCategory($category);

        return $this->renderView(
            'category',
            [
                'category' => $category,
                'posts'    => $posts,
            ],
            seo_title($category->name),
            $category,
            $posts->first()
        );
    }

    /**
     * Render posts filtered by date.
     */
    public function renderDateView(string $value, string $type, ?string $year = null): View
    {
        static $months = [
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
        $displayValue = $isMonth ? ($months[(int)$value] ?? '') : $value;
        $yearMonth = $isMonth ? ($year ?? date('Y')) . '/' . $value : $value;

        $seoTitle = $isMonth
            ? 'Posts for the month of ' . $displayValue
            : 'Posts for the year - ' . $value;

        return $this->renderView('date', [
            'posts' => $this->categoryService->getPostsByDate($yearMonth),
        ], seo_title($seoTitle));
    }

    /**
     * Centralized render helper with optional common data.
     */
    private function renderView(string $view, array $data = [], string $seoTitle = '', ?Category $category = null, $firstItem = null): View
    {
        $data['seoTitle'] = $seoTitle;

        return $this->render($view, $data, $this->buildCommonViewData($category, $firstItem));
    }
}
