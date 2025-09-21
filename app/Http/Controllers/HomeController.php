<?php

namespace App\Http\Controllers;

use Atannex\Binders\PassPosts;
use Illuminate\Contracts\Support\Renderable;

class HomeController extends Controller
{
    public function __construct(
        protected readonly PassPosts $postService
    ) {}

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        $todayPosts  = $this->postService->getTodayPosts(6);
        $recentPosts = $this->postService->getRecentPosts(6);
        $regions     = $this->postService->getRegionWithPost(6);
        $editorPicks = $this->postService->getEditorPick(10);

        $allPosts = $todayPosts->isNotEmpty()
            ? $todayPosts->take(6)
            : $recentPosts->take(6);

        $heroTitle = $todayPosts->isNotEmpty()
            ? __("Today Updates")
            : __("Recent Updates");


        $featuredBlog = $todayPosts->first();
        $sideBlogs    = $todayPosts->take(3)->skip(1);

        return view('home', ['heroTitle'=> $heroTitle ,'allPosts' => $allPosts, 'editorPicks' => $editorPicks, 'sideBlogs' => $sideBlogs, 'featuredBlog' => $featuredBlog, 'regions' => $regions, 'todayPosts' => $todayPosts]);
    }
}
