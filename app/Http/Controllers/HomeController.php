<?php

namespace App\Http\Controllers;

use Atannex\Binders\PassPosts;
use Illuminate\Support\Collection;
use Illuminate\View\View;

/**
 * Class HomeController
 *
 * Handles the rendering of the application's dashboard.
 */
class HomeController extends Controller
{
    /**
     * HomeController constructor.
     *
     * @param PassPosts $postService The service for retrieving post data.
     */
    public function __construct(
        protected readonly PassPosts $postService
    ) {}

    /**
     * Displays the application dashboard with curated posts.
     *
     * Fetches various types of posts (today's, recent, editor's picks, etc.) and
     * prepares data for the home view. Handles fallback logic if no posts are available.
     *
     * @return View
     */
    public function index(): View
    {
        $todayPosts = $this->postService->getTodayPosts(6);
        $recentPosts = $this->postService->getRecentPosts(6);
        $regions = $this->postService->getRegionWithPost(6);
        $editorPicks = $this->postService->getEditorPick(10);
        // $featuredPosts = $this->postService->getFeaturedPosts(5);
        $featuredPosts = $this->postService->getPopularPosts(5);
        $mostReadPosts = $this->postService->getMostReadPosts(6);
        $popularPosts = $this->postService->getPopularPosts(5);

        $allPosts = $this->getMainPosts($todayPosts, $recentPosts);
        $heroTitle = $this->getHeroTitle($todayPosts);

        $featuredBlog = $todayPosts->first();
        $sideBlogs = $todayPosts->skip(1)->take(2);


        $viewData = [
            'heroTitle' => $heroTitle,
            'allPosts' => $allPosts,
            'editorPicks' => $editorPicks,
            'sideBlogs' => $sideBlogs,
            'featuredBlog' => $featuredBlog,
            'regions' => $regions,
            'todayPosts' => $todayPosts,
            'featuredPosts' => $featuredPosts,
            'mostReadPosts' => $mostReadPosts,
            'popularPosts' => $popularPosts,
        ];

        return view('home', $viewData);
    }

    /**
     * Determines the main posts to display based on availability.
     *
     * @param Collection $todayPosts Today's posts collection.
     * @param Collection $recentPosts Recent posts collection.
     * @return Collection The selected posts collection.
     */
    private function getMainPosts(Collection $todayPosts, Collection $recentPosts): Collection
    {
        return $todayPosts->isNotEmpty()
            ? $todayPosts->take(6)
            : $recentPosts->take(6);
    }

    /**
     * Determines the hero title based on post availability.
     *
     * @param Collection $todayPosts Today's posts collection.
     * @return string The hero title.
     */
    private function getHeroTitle(Collection $todayPosts): string
    {
        return $todayPosts->isNotEmpty()
            ? __('Today Updates')
            : __('Recent Updates');
    }
}
