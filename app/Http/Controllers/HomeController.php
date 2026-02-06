<?php

namespace App\Http\Controllers;

use App\Http\Traits\HasAbout;
use App\Http\Traits\HasContact;
use App\Models\Posts\Video;
use Atannex\Binders\HasPost;
use Illuminate\View\View;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    use HasAbout;
    use HasContact;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected readonly HasPost $postService
    ) {}

    /**
     * Display the home page.
     */
    public function index(): View
    {
        return view('home', array_merge(
            $this->getPostsData(),
            [
                'videos' => $this->videos(6),
            ]
        ));
    }

    /**
     * Prepare categorized post collections for the home view.
     *
     * @return array<string, mixed>
     */
    private function getPostsData(): array
    {
        return [
            'byRecent'       => $this->postService->hasRecentPosts(6),
            'byRegion'       => $this->postService->hasRegionWithPost(6),
            'byEnvironment'  => $this->postService->categoriesWithPostsByName('Environment', 5),
            'byHistory'      => $this->postService->categoriesWithPostsByName('History', 20),
            'byNews'         => $this->postService->categoriesWithPostsByName('News', 3),
            'byCommunity'    => $this->postService->categoriesWithPostsByName('Community', 10),
            'byRuler'        => $this->postService->categoriesWithPostsByName('Rulers', 10),
        ];
    }

    /**
     * Retrieve the most recent published videos
     * that are associated with published posts.
     *
     * @param int $limit Maximum number of videos to return.
     * @return \Illuminate\Support\Collection
     */
    private function videos(int $limit = 6): Collection
    {
        return Video::query()
            ->with([
                'post:id,title,slug,slug_path,published_at,category_id,author_id',
                'post.category:id,name,slug_path',
                'post.author:id,user_id',
                'post.author.user:id,name,slug',
            ])
            ->published()
            ->whereHas('post', static function ($query) {
                $query->published();
            })
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }
}
