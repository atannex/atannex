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

    public function __construct(
        protected readonly RegionService $regionService,
        protected readonly Atannex $atannex,
        protected readonly TagService $tagService,
        protected readonly CategoryService $categoryService,
        protected readonly HasPost $getPost,
        protected readonly ShareService $shareService,
    ) {}

    protected function getAllShareIcons(): array
    {
        return collect(self::SUPPORTED_PLATFORMS)
            ->mapWithKeys(fn(string $platform) => [$platform => Icon::getData($platform)])
            ->all();
    }

    protected function renderView(string $view, array $data = [], string $seoTitle = ''): View
    {
        return view($view, array_merge($data, ['seoTitle' => $seoTitle ?: '']));
    }

    /**
     * Safely get recent posts, avoiding errors when no reference post exists.
     */
    protected function getRecentPosts(?object $referencePost, int $limit = 6): Collection
    {
        return $referencePost
            ? $this->categoryService->recentPosts($referencePost, $limit)
            : collect();
    }

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
