<?php

namespace App\Http\Controllers;

use App\Http\Traits\HasAbout;
use App\Http\Traits\HasContact;
use Atannex\Services\PostService;
use Illuminate\View\View;

class HomeController extends Controller
{
    use HasAbout;
    use HasContact;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected readonly PostService $postService,
    ) {}

    /**
     * Display the home page.
     */
    public function index(): View
    {
        return view('home', array_merge($this->getPostsData(), ['videos' => $this->postService->getLatestPublishedVideos(6)]));
    }

    public function landing(): View
    {
        $posts = $this->postService->getRecentPosts(6);

        return view('landing.index', compact('posts'));
    }

    public function donate(): View
    {
        return view('landing.donate');
    }

    public function category(): View
    {
        return view('landing.category');
    }

    public function checkout(): View
    {
        return view('landing.checkout');
    }

    public function confirm(): View
    {
        return view('landing.confirmation');
    }

    public function gallery(): View
    {
        return view('landing.gallery');
    }

    public function testimonials(): View
    {
        return view('landing.testimonials');
    }

    /**
     * Prepare categorized post collections for the home view.
     *
     * @return array<string, mixed>
     */
    private function getPostsData(): array
    {
        return [
            'byRecent' => $this->postService->getRecentPosts(6),
            'byRegion' => $this->postService->getRegionsWithPosts(5),
            'byEnvironment' => $this->postService->getCategoriesWithPostsByName('Environment', 5),
            'byHistory' => $this->postService->getCategoriesWithPostsByName('History', 20),
            'byNews' => $this->postService->getCategoriesWithPostsByName('News', 3),
            'byCommunity' => $this->postService->getCategoriesWithPostsByName('Community', 10),
            'byRuler' => $this->postService->getCategoriesWithPostsByName('Rulers', 10),
        ];
    }
}
