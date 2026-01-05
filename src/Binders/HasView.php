<?php

namespace Atannex\Binders;

use Atannex\Facades\Atannex;
use Atannex\Services\CategoryService;
use Atannex\Services\RegionService;
use Atannex\Services\ShareService;
use Atannex\Services\TagService;
use App\Enums\Icon;
use App\Enums\PostType;
use App\Models\Modules\PostModule;
use App\Models\Regions\Category;
use App\Models\Regions\Employee;
use App\Models\Regions\Region;
use App\Models\Tags\Tag;
use App\Enums\Traits\HasEntityMapping;
use Atannex\Traits\Resolution;
use Illuminate\View\View;
use Illuminate\Support\Collection;

final class HasView
{
    use HasEntityMapping;
    use Resolution;

    public const SUPPORTED_PLATFORMS = [
        Icon::FACEBOOK,
        Icon::TWITTER,
        Icon::WHATSAPP,
        Icon::TELEGRAM,
    ];

    /**
     * Initialize the HasView instance with required service dependencies.
     *
     * Stores the provided services as readonly properties used for resolving regions,
     * posts, tags, categories, annex data, and share icon handling during view rendering.
     */
    public function __construct(
        protected readonly RegionService $regionService,
        protected readonly Atannex $atannex,
        protected readonly TagService $tagService,
        protected readonly CategoryService $categoryService,
        protected readonly HasPost $getPost,
        protected readonly ShareService $shareService,
    ) {}

    /**
     * Provide icon data for each supported sharing platform.
     *
     * @return array<string, mixed> Associative array mapping each platform identifier from SUPPORTED_PLATFORMS to its icon data.
     */
    protected function getAllShareIcons(): array
    {
        return collect(self::SUPPORTED_PLATFORMS)
            ->mapWithKeys(fn(string $platform) => [$platform => Icon::getData($platform)])
            ->all();
    }

    /**
     * Render a view with provided data and an explicit SEO title variable.
     *
     * Merges the supplied data array with a `seoTitle` entry and returns the resulting view.
     *
     * @param string $view The view template name.
     * @param array $data Associative data passed to the view.
     * @param string $seoTitle The SEO title to expose to the view; empty string if none.
     * @return \Illuminate\View\View The rendered view instance.
     */
    protected function renderView(string $view, array $data = [], string $seoTitle = ''): View
    {
        return view($view, array_merge($data, ['seoTitle' => $seoTitle ?: '']));
    }

    /**
     * Retrieve recent posts related to a reference post, or an empty collection if none.
     *
     * @param object|null $referencePost The reference post used to find recent posts; pass null to receive an empty collection.
     * @param int $limit Maximum number of posts to return.
     * @return \Illuminate\Support\Collection A collection of recent posts, or an empty collection when no reference post is provided.
     */
    protected function getRecentPosts(?object $referencePost, int $limit = 6): Collection
    {
        return $referencePost
            ? $this->categoryService->recentPosts($referencePost, $limit)
            : collect();
    }

    /**
     * Collects popular tags and categories related to a given tag.
     *
     * If `$tag` is null, returns empty collections for both values.
     *
     * @param Tag|null $tag The reference tag to find related data for.
     * @param int $limit Maximum number of popular tags to return.
     * @return array{popularTags:\Illuminate\Support\Collection, relatedCategories:\Illuminate\Support\Collection} An array with keys:
     *  - `popularTags`: a collection of popular tags related to `$tag`.
     *  - `relatedCategories`: a collection of categories related to `$tag`.
     */
    protected function getPopularAndRelatedData(?Tag $tag, int $limit = 8): array
    {
        if (!$tag) {
            return [
                'popularTags' => collect(),
                'relatedCategories' => collect(),
            ];
        }

        return [
            'popularTags' => $this->categoryService->popularTags($tag, $limit),
            'relatedCategories' => $this->categoryService->relatedCategoriesByTag($tag),
        ];
    }

    /**
     * Render the region listing page including posts, recent posts, and tag-related data.
     *
     * Aborts with a 404 response if the provided region model does not exist.
     *
     * @param Region $region The region model to render.
     * @return View The rendered view for the region page.
     */
    public function renderRegionView(Region $region): View
    {
        abort_unless($region->exists, 404);

        $this->resolveSection($region);

        $posts = $this->categoryService->postsByRegion($region);
        $firstPost = $posts->first();
        $firstTag = $firstPost?->tags->first();

        $tagData = $this->getPopularAndRelatedData($firstTag);

        return $this->renderView('region', array_merge([
            'region' => $region,
            'posts' => $posts,
            'recentPosts' => $this->getRecentPosts($firstPost),
        ], $tagData), seo_title($region->name));
    }

    /**
     * Render the tag listing page including posts, recent posts, and related tag/category data.
     *
     * @param Tag $tag The tag model to build the view for.
     * @return View A view instance for the tag page populated with `tag`, `posts`, `recentPosts`, and related data.
     */
    public function renderTagView(Tag $tag): View
    {
        $posts = $this->categoryService->postsByTag($tag);
        $firstPost = $posts->first();

        $tagData = $this->getPopularAndRelatedData($tag);

        return $this->renderView('tag', array_merge([
            'tag' => $tag,
            'posts' => $posts,
            'recentPosts' => $this->getRecentPosts($firstPost),
        ], $tagData), seo_title($tag->name));
    }

    /**
     * Render the author page for a given employee.
     *
     * @param Employee $author The employee whose author page to render. Must have an associated `user` relation; otherwise the request aborts with a 404.
     * @return View A view instance for the rendered author page.
     */
    public function renderAuthorView(Employee $author): View
    {
        if (!$author->user) {
            abort(404, 'Author not found');
        }

        return $this->renderView('author', [
            'author' => $author,
            'posts' => $this->categoryService->postsByAuthor($author->user->slug),
            'user_medias' => $this->categoryService->employeeSocial($author),
        ], seo_title($author->name));
    }

    /**
     * Render the category page populated with the category's posts, recent posts, related categories, and popular tags.
     *
     * @param Category $category The category to render.
     * @return View The rendered category view containing:
     *              - `category`: the given Category,
     *              - `posts`: posts belonging to the category,
     *              - `recentPosts`: a collection of recent posts related to the first post,
     *              - `relatedCategories`: categories related to the given category,
     *              - `popularTags`: popular tags derived from the first post's first tag (empty collection if none).
     */
    public function renderCategoryView(Category $category): View
    {
        $posts = $this->categoryService->postsByCategory($category);
        $firstPost = $posts->first();
        $firstTag = $firstPost?->tags->first();

        return $this->renderView('category', [
            'category' => $category,
            'posts' => $posts,
            'recentPosts' => $this->getRecentPosts($firstPost),
            'relatedCategories' => $this->categoryService->relatedCategories($category),
            'popularTags' => $firstTag
                ? $this->categoryService->popularTags($firstTag, 8)
                : collect(),
        ], seo_title($category->name));
    }

    /**
     * Render the date archive view for either an entire year or a specific month.
     *
     * @param string $year Four-digit year (must be between 1900 and next year).
     * @param string $type Archive granularity: 'year' for a full-year view or 'month' for a month-specific view.
     * @param string|null $month Two-digit month string ('01'–'12') required when `$type` is 'month'; otherwise null.
     * @return \Illuminate\View\View The rendered view populated with posts for the specified period and related display data.
     */
    public function renderDateView(string $year, string $type = 'year', ?string $month = null): View
    {
        // Validate year
        if (!preg_match('/^\d{4}$/', $year) || $year < 1900 || $year > date('Y') + 1) {
            abort(404, 'Invalid year');
        }

        $months = config('dates.months', []);
        $isMonthView = $type === 'month';

        if ($isMonthView) {
            // Validate month format (01–12)
            if (!preg_match('/^\d{2}$/', $month) || $month < '01' || $month > '12') {
                abort(404, 'Invalid month');
            }

            $monthInt = (int) $month;
            $displayValue = $months[$monthInt] ?? 'Unknown Month';
            $yearMonth = "{$year}/{$month}";
            $seoTitle = "Posts for {$displayValue} {$year}";
        } else {
            $displayValue = $year;
            $yearMonth = $year;
            $seoTitle = "Posts for the year {$year}";
        }

        return $this->renderView('date', [
            'posts' => $this->categoryService->postsByDate($yearMonth),
            'displayValue' => $displayValue,
            'type' => $type,
            'year' => $year,
            'month' => $month,
        ], seo_title($seoTitle));
    }

    /**
     * Render the post show page for a given category and post slug.
     *
     * Gathers the post module (including author, tags, and category), selects the appropriate
     * view template based on the module type, and provides related data (popular and related tags,
     * navigation, related categories, recent and related posts, author media, and share icons)
     * to the view.
     *
     * @param Category $category The category context used to compute related categories.
     * @param string $slug The post's slug_path used to locate the post.
     * @return View The rendered view for the requested post.
     */
    public function renderPostShow(Category $category, string $slug): View
    {
        $module = PostModule::with(['post.author.user', 'post.tags', 'post.category'])
            ->whereHas('post', fn($q) => $q->where('slug_path', $slug))
            ->firstOrFail();

        $post = $module->post;

        if (!$post->author) {
            abort(500, 'Post missing author');
        }

        $viewTemplate = match ($module->type->value) {
            PostType::VIDEO => 'shows.video',
            PostType::AUDIO => 'shows.audio',
            default => 'shows.index',
        };

        return $this->renderView($viewTemplate, [
            'module' => $module,
            'post' => $post,
            'popularTags' => $this->tagService->getPopularTags(),
            'relatedTags' => $this->tagService->getTagsForPost($post->id),
            'navigation' => $this->getPost->hasPostNavigation($post),
            'relatedCategories' => $this->categoryService->relatedCategories($category),
            'recentPosts' => $this->categoryService->recentPosts($post),
            'relatedPosts' => $this->getPost->hasRelatedPosts($post),
            'medias' => $this->categoryService->employeeSocial($post->author),
            'icons' => $this->getAllShareIcons(),
        ], seo_title($post->title));
    }
}