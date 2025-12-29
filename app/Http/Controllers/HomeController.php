<?php

namespace App\Http\Controllers;

use App\Http\Traits\HasAbout;
use App\Http\Traits\HasContact;
use Atannex\Binders\HasPost;
use Illuminate\Support\Collection;
use Illuminate\View\View;

/**
 * Class HomeController
 *
 * Handles the rendering of the application's homepage/dashboard.
 */
class HomeController extends Controller
{
    use HasAbout;
    use HasContact;

    /**
     * HomeController constructor.
     *
     * @param  PassPosts  $postService  Service for retrieving post data.
     */
    public function __construct(
        protected readonly HasPost $postService
    ) {}

    /**
     * Displays the application homepage with curated posts.
     */
    public function index(): View
    {
        $postsData = $this->getPostsData();

        $viewData = [
            'heroTitle' => $this->getHeroTitle($postsData['todayPosts']),
            'recentPosts' => $postsData['recentPosts'],
            'editorPicks' => $postsData['editorPicks'],
            'sideBlogs' => $this->getSideBlogs($postsData['todayPosts']),
            'featuredBlog' => $this->getFeaturedBlog($postsData['todayPosts']),
            'regions' => $postsData['regions'],
            'todayPosts' => $postsData['todayPosts'],
            'featuredPosts' => $postsData['featuredPosts'],
            'mostReadPosts' => $postsData['mostReadPosts'],
            'popularPosts' => $postsData['popularPosts'],
        ];

        return view('home', $viewData);
    }

    /**
     * Retrieves all required posts for the homepage.
     *
     * @return array<string, Collection>
     */
    private function getPostsData(): array
    {
        return [
            'getPastWeekPosts' => $this->postService->getPastWeekPosts(6),
            'recentPosts' => $this->postService->getRecentPosts(6),
            'regions' => $this->postService->getRegionWithPost(6),
            'editorPicks' => $this->postService->getEditorPick(10),
            'featuredPosts' => $this->postService->getPopularPosts(5),
            'mostReadPosts' => $this->postService->getMostReadPosts(6),
            'popularPosts' => $this->postService->getPopularPosts(5),
        ];
    }

    /**
     * Determines the hero title based on post availability.
     */
    private function getHeroTitle(Collection $getPastWeekPosts): string
    {
        return $getPastWeekPosts->isNotEmpty()
            ? __('Weekly Updates')
            : __('Recent Updates');
    }

    /**
     * Gets the featured blog (first from today's posts).
     */
    private function getFeaturedBlog(Collection $getPastWeekPosts): ?object
    {
        return $getPastWeekPosts->first();
    }

    /**
     * Gets the side blogs (after the featured blog).
     */
    private function getSideBlogs(Collection $getPastWeekPosts): Collection
    {
        return $getPastWeekPosts->skip(1)->take(2);
    }
}
