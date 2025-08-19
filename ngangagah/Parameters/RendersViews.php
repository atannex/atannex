<?php

namespace Ngangagah\Parameters;

use Illuminate\View\View;
use App\Models\Posts\Post;
use App\Models\Pages\Category;
use App\Models\Pivots\PostTag;
use App\Models\Regions\Employee;
use App\Models\Modules\PostModule;
use Ngangagah\Parameters\Traits\SocialShare;

trait RendersViews
{
    use PageContent;
    use SocialShare;

    /**
     * Render a view with merged data.
     */
    protected function render(string $view, array $data = [], array $extra = []): View
    {
        return view($view, array_merge($data, $extra));
    }

    /**
     * Build common view data for categories/posts.
     */
    private function buildCommonViewData(?Category $category = null, ?Post $post = null): array
    {
        return [
            'popularTags'      => $this->tagService->getPopularTags(),
            'relatedTags'      => $post ? $this->tagService->getTagsForPost($post->id) : collect(),
            'navigation'       => $post ? $this->postService->getPostNavigation($post) : collect(),
            'relatedCategories' => $category ? $this->categoryService->getRelatedCategoriesForCategory($category) : collect(),
            'recentPosts'      => $post ? $this->categoryService->getRecentPosts($post) : collect(),
            'relatedPosts'     => $post ? $this->postService->getRelatedPosts($post) : collect(),
        ];
    }

    /**
     * Render a single post page.
     */
    protected function renderPostShow(Category $category, string $slug): View
    {
        $post   = Post::where('slug_path', $slug)->firstOrFail();
        $module = PostModule::with('post')
            ->whereHas('post', fn($q) => $q->where('slug_path', $slug))
            ->firstOrFail();

        return $this->render('shows.index', [
            'module'  => $module,
            'medias'  => $this->categoryService->getPublishedEmployeeSocialMedia($module->post->author),
            'shares'  => $this->buildSocialShareData($module->post),
        ], $this->buildCommonViewData($category, $post));
    }

    /**
     * Render posts associated with a specific tag.
     */
    protected function renderTagView(PostTag $postTag): View
    {
        $tag   = $postTag->tag;
        $first = $tag->posts->first();

        return $this->render('tag', [
            'seoTitle'         => seo_title($tag->name),
            'tag'              => $tag,
            'posts'            => $this->categoryService->getPostsByTag($tag),
            'relatedCategories' => $this->categoryService->getRelatedCategoriesForTag($tag),
            'recentPosts'      => $first ? $this->categoryService->getRecentPosts($first, 6) : collect(),
            'popularTags'      => $this->tagService->getPopularTags(8),
        ]);
    }

    /**
     * Render a static page.
     */
    protected function renderPageView(string $slug): View
    {
        $page = $this->pageService->getHomePage($slug);
        $this->resolveContent($page, $slug);

        return $this->render('pages', ['page' => $page], $this->buildCommonViewData());
    }

    /**
     * Render a category page with related content.
     */
    protected function renderCategoryView(Category $category): View
    {
        $posts = $this->categoryService->getPostsByCategory($category);

        return $this->render('category', [
            'category' => $category,
            'posts'    => $posts,
        ], $this->buildCommonViewData($category, $posts->first()));
    }

    /**
     * Render the author profile page.
     */
    protected function renderAuthorView(Employee $author): View
    {
        return $this->render('author', [
            'author'      => $author,
            'posts'       => $this->categoryService->getPostsByAuthor($author->user->slug),
            'user_medias' => $this->categoryService->getPublishedEmployeeSocialMedia($author),
        ], $this->buildCommonViewData());
    }
}
