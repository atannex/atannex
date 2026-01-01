<?php

namespace App\Http\Controllers;

use App\Http\Traits\HasAbout;
use App\Http\Traits\HasContact;
use Atannex\Binders\HasPost;
use Illuminate\View\View;

class HomeController extends Controller
{
    use HasAbout;
    use HasContact;

    public function __construct(
        protected readonly HasPost $postService
    ) {}

    public function index(): View
    {
        $postsData = $this->getPostsData();

        return view('home', [
            'byRecent' => $postsData['byRecent'],
            'byRegion' => $postsData['byRegion'],
            'byEnvironment' => $postsData['byEnvironment'],
            'byHistory' => $postsData['byHistory'],
            'byCommunity' => $postsData['byCommunity'],
            'byNews' => $postsData['byNews'],
            'byRuler' => $postsData['byRuler'],
        ]);
    }

    private function getPostsData(): array
    {
        return [
            'byRecent' => $this->postService->hasRecentPosts(6),
            'byRegion' => $this->postService->hasRegionWithPost(6),
            'byEnvironment' => $this->postService->categoriesWithPostsByName('Environment', 5),
            'byHistory' => $this->postService->categoriesWithPostsByName('History', 20),
            'byNews' => $this->postService->categoriesWithPostsByName('Community', 3),//News Category
            'byCommunity' => $this->postService->categoriesWithPostsByName('Community', 10),
            'byRuler' => $this->postService->categoriesWithPostsByName('Community', 10), // Rulers Category
        ];
    }
}
