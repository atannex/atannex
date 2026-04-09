<?php

declare(strict_types=1);

namespace Atannex\Binders;

use App\Enums\Icon;
use App\Enums\Traits\HasEntityMapping;
use App\Models\Regions\Region;
use Atannex\Facades\Atannex;
use Atannex\Services\AuthorService;
use Atannex\Services\CategoryService;
use Atannex\Services\PostService;
use Atannex\Services\RegionService;
use Atannex\Services\ShareService;
use Atannex\Services\TagService;
use Atannex\Traits\HandlesPostDateResolution;
use Atannex\Traits\ResolvesDynamicContent;
use Illuminate\View\View;

final class HasView
{
    use HasEntityMapping;
    use HandlesPostDateResolution;
    use ResolvesDynamicContent;

    public function __construct(
        protected readonly RegionService $regionService,
        protected readonly Atannex $atannex,
        protected readonly TagService $tagService,
        protected readonly CategoryService $categoryService,
        protected readonly HasPost $getPost,
        protected readonly ShareService $shareService,
        protected readonly PostService $postService,
        protected readonly AuthorService $authorService,
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Post Show View
    |--------------------------------------------------------------------------
    */

    public const SUPPORTED_PLATFORMS = [
        Icon::FACEBOOK,
        Icon::TWITTER,
        Icon::WHATSAPP,
        Icon::TELEGRAM,
    ];

    /*
    |--------------------------------------------------------------------------
    | Region Views
    |--------------------------------------------------------------------------
    */

    public function renderRegionView(Region $region): View
    {
        $this->resolveSection($region);

        return view('region', [
            'region'            => $region,
            'posts'             => $this->regionService->postsByRegion($region),
            'recentPosts'       => $this->regionService->recentPostsByRegion($region, 6),
            'popularTags'       => $this->categoryService->popularTagsByRegion($region, 8),
            'relatedCategories' => $this->categoryService->relatedCategoriesByRegion($region),
            'seoTitle'          => seo_title($region->name),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Author, Category & Tag Views
    |--------------------------------------------------------------------------
    */

    public function renderAuthorView(string $slug): View
    {
        $author = $this->authorService->resolveAuthorBySlug($slug);

        return view('author', [
            'author'       => $author,
            'posts'        => $this->authorService->postsByAuthor($author->user->slug),
            'user_medias'  => $this->categoryService->employeeSocial($author),
            'seoTitle'     => seo_title($author->name),
        ]);
    }

    public function renderCategoryView(string $slug): View
    {
        $category = $this->categoryService->resolveCategoryBySlug($slug);

        return view('category', [
            'category'          => $category,
            'posts'             => $this->categoryService->postsByCategory($category),
            'recentPosts'       => $this->categoryService->recentPostsByCategory($category, 6),
            'relatedCategories' => $this->categoryService->relatedCategories($category),
            'popularTags'       => $this->categoryService->popularTagsByCategory($category, 8),
            'seoTitle'          => seo_title($category->name),
        ]);
    }

    public function renderTagView(string $slug): View
    {
        $tag = $this->tagService->getTagBySlug($slug);
        $posts = $this->categoryService->postsByTag($tag);
        $firstPost = $posts->firstOrFail();
        $category = $firstPost->category;

        return view('tag', [
            'tag'               => $tag,
            'posts'             => $posts,
            'recentPosts'       => $this->tagService->getRecentPostsForTag($tag, 6),
            'popularTags'       => $this->categoryService->popularTagsByCategory($category, 8),
            'relatedCategories' => $this->categoryService->relatedCategoriesByTag($tag),
            'seoTitle'          => seo_title($tag->name),
        ]);
    }

    public function renderYearView(string $year): View
    {
        $resolution = $this->resolvePostArchiveYear($year);

        if (! $resolution) {
            abort(404);
        }

        return $this->renderArchiveView($resolution);
    }

    public function renderMonthView(string $year, string $month): View
    {
        $resolution = $this->resolvePostArchiveMonth($year, $month);

        if (! $resolution) {
            abort(404);
        }

        return $this->renderArchiveView($resolution);
    }

    protected function renderArchiveView(array $resolution): View
    {
        $year = (string) $resolution['year'];
        $month = $resolution['month'] !== null
            ? str_pad((string) $resolution['month'], 2, '0', STR_PAD_LEFT)
            : null;

        $isMonth = $resolution['type'] === 'month';
        $displayValue = $isMonth
            ? $this->formatMonthDisplay((int) $resolution['month'], $year)
            : $year;

        $seoTitle = $isMonth
            ? "Posts for {$displayValue}"
            : "Posts for the year {$year}";

        $posts = $isMonth
            ? $this->categoryService->postsByMonth($year, $month)
            : $this->categoryService->postsByYear($year);

        return view('date', [
            'posts'        => $posts,
            'displayValue' => $displayValue,
            'period'       => $isMonth ? "{$year}/{$month}" : $year,
            'type'         => $resolution['type'],
            'year'         => $year,
            'month'        => $month,
            'seoTitle'     => seo_title($seoTitle),
        ]);
    }

    private function formatMonthDisplay(int $month, string $year): string
    {
        $monthName = config("dates.months.{$month}", 'Unknown Month');

        return "{$monthName} {$year}";
    }

    public function renderPostShow(string $slug): View
    {
        $module = $this->postService->getPostBySlugPath($slug);
        $post = $module->post;

        $icons = collect(self::SUPPORTED_PLATFORMS)
            ->mapWithKeys(fn(string $platform) => [$platform => Icon::getData($platform)])
            ->toArray();

        return view('shows.index', [
            'headingLevels'     => \App\Enums\HeadingLevel::asSelectArray(),
            'module'            => $module,
            'post'              => $post,

            'popularTags'       => $this->tagService->popularTagsByPost($post),
            'relatedTags'       => $this->tagService->relatedTagsByPost($post),
            'relatedCategories' => $this->categoryService->relatedCategoriesByPost($post),
            'recentPosts'       => $this->postService->getRecentPostsFromSameCategory($post),
            'relatedPosts'      => $this->postService->getRelatedPosts($post),

            'navigation'        => $this->postService->getPostNavigation($post),
            'medias'            => $this->categoryService->employeeSocial($post->author),
            'icons'             => $icons,
            'seoTitle'          => seo_title($post->title ?? $post->slug),
        ]);
    }
}
