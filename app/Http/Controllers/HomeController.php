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

    /**
     * Instantiate the HomeController with its post service dependency.
     *
     * @param HasPost $postService Service used to fetch and query posts for the controller.
     */
    public function __construct(
        protected readonly HasPost $postService
    ) {}

    /**
     * Render the home view populated with categorized post collections.
     *
     * The view data includes the following keys populated from the post service:
     * `byRecent`, `byRegion`, `byEnvironment`, `byHistory`, `byCommunity`, `byNews`, and `byRuler`.
     *
     * @return \Illuminate\View\View The rendered 'home' view populated with the categorized post data.
     */
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

    /**
     * Assembles categorized post collections for the home view.
     *
     * Returns an associative array keyed for the view where each value is a collection
     * or iterable of posts constrained by category or other criteria:
     * - `byRecent`: recent posts limited to 6.
     * - `byRegion`: regions that have posts limited to 6.
     * - `byEnvironment`: posts in the "Environment" category limited to 5.
     * - `byHistory`: posts in the "History" category limited to 20.
     * - `byNews`: posts intended for the News section (currently sourced from "Community") limited to 3.
     * - `byCommunity`: posts in the "Community" category limited to 10.
     * - `byRuler`: posts intended for the Rulers section (currently sourced from "Community") limited to 10.
     *
     * @return array<string, mixed> Associative array of categorized post collections for the view.
     */
    private function getPostsData(): array
    {
        return [
            'byRecent' => $this->postService->hasRecentPosts(6),
            'byRegion' => $this->postService->hasRegionWithPost(6),
            'byEnvironment' => $this->postService->categoriesWithPostsByName('Environment', 5),
            'byHistory' => $this->postService->categoriesWithPostsByName('History', 20),
            'byNews' => $this->postService->categoriesWithPostsByName('News', 3),
            'byCommunity' => $this->postService->categoriesWithPostsByName('Community', 10),
            'byRuler' => $this->postService->categoriesWithPostsByName('Rulers', 10),
        ];
    }
}
