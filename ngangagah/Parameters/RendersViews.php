<?php

namespace Ngangagah\Parameters;

use Illuminate\View\View;
use App\Models\Posts\Post;
use App\Models\Pages\Category;
use App\Models\Regions\Employee;
use App\Models\Modules\PostModule;
use Ngangagah\Handlers\Traits\GetPostNavigation;
use Ngangagah\Handlers\Traits\GetRelatedPost;

/**
 * Trait RendersViews
 *
 * Provides reusable rendering methods for various types of pages and
 * guarantees stable view data keys to avoid undefined variable errors.
 */
trait RendersViews
{
    use PageContent;
    use GetRelatedPost;
    use GetPostNavigation;

    /**
     * Render a view with the given data.
     */
    protected function render(string $view, array $data = []): View
    {
        return view($view, $data);
    }


    private function getCommonViewData(?Category $category = null, ?Post $post = null): array
    {
        $popularTags = $this->tagService->getPopularTags();

        $relatedTags = $post ? $this->tagService->getTagsForPost($post->id) : collect();

        $navigation = $post ? $this->getPostNavigation($post) : collect();

        $relatedCategories = $category
            ? $this->categoryService->getRelatedCategoriesForCategory($category)
            : collect();

        $recentPosts = $post
            ? $this->categoryService->getRecentPosts($post)
            : collect();

        $relatedPosts = $post
            ? $this->getRelatedPosts($post)
            : collect();

        return compact(
            'popularTags',
            'relatedTags',
            'navigation',
            'relatedCategories',
            'recentPosts',
            'relatedPosts'
        );
    }

    /**
     * Render a static page.
     */
    protected function renderPageView(string $slug): View
    {
        $page = $this->pageService->getHomePage($slug);
        $this->resolveContent($page, $slug);

        return $this->render('pages', array_merge(
            compact('page'),
            $this->getCommonViewData()
        ));
    }

    /**
     * Render a category page with related content.
     */
    protected function renderCategoryView(Category $category): View
    {
        $posts = $this->categoryService->getPostsByCategory($category);
        $firstPost = $posts->first();

        $data = array_merge(
            compact('category', 'posts'),
            $this->getCommonViewData($category, $firstPost)
        );

        return $this->render('category', $data);
    }

    /**
     * Render a single post page.
     */
    protected function renderPostShow(Category $category, string $slug): View
    {
        $post = Post::where('slug_path', $slug)->firstOrFail();

        $module = PostModule::whereHas('post', function ($query) use ($slug) {
            $query->where('slug_path', $slug);
        })
            ->with('post')
            ->firstOrFail();

        $data = array_merge(
            compact('module'),
            $this->getCommonViewData($category, $post)
        );

        return $this->render('shows.index', $data);
    }

    /**
     * Render the author profile page.
     */
    protected function renderAuthorView(Employee $author): View
    {
        $data = array_merge(
            [
                'author' => $author,
                'posts' => $this->categoryService->getPostsByAuthor($author->user->slug),
                'user_medias' => $this->categoryService->getPublishedEmployeeSocialMedia($author),
            ],
            $this->getCommonViewData()
        );

        return $this->render('author', $data);
    }
}
