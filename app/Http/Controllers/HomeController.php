<?php

namespace App\Http\Controllers;

use Atannex\Binders\GetPost;

class HomeController extends Controller
{

    public function __construct(protected readonly GetPost $postService) {}
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $recentPosts = $this->postService->getRecentPublishedPosts(5);
        // $editorPicks = $this->postService->getEditorPicks();
        $editorPicks = $this->postService->getRecentPublishedPosts(20);
        $featuredPost = $editorPicks->first();
        $smallPosts = $editorPicks->take(5)->skip(1);

        return view('home', compact('recentPosts', 'editorPicks', 'featuredPost', 'smallPosts'));
    }
}
