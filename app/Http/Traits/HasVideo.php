<?php

namespace App\Http\Traits;

use App\Models\Posts\Video;
use App\Models\Posts\VideoModule;
use Illuminate\View\View;

trait HasVideo
{
    /**
     * Get the list of published videos for a view.
     */
    public function video(): View
    {
        $videos = Video::published()->get();

        return view('videos.video', compact('videos'));
    }

    /**
     * Show the catalog page for videos.
     */
    public function catalog(): View
    {
        $videos = Video::published()->get();

        return view('videos.catalog', compact('videos'));
    }

    /**
     * Show a single video page by slug.
     */
    public function show(string $slug): View
    {
        $module = VideoModule::with('video')
            ->whereHas('video', fn($query) => $query->where('slug', $slug)->published())
            ->firstOrFail();

        $video = $module->video;

        $images = collect($module->images)->map(fn($img) => [
            'file' => $img['file'] ?? null,
            'caption' => $img['caption'] ?? null,
            'alt' => $img['alt'] ?? null,
        ]);

        $relatedVideos = $this->relatedByCategory($video);

        return view('videos.show', compact('video', 'module', 'images', 'relatedVideos'));
    }


    /**
     * Retrieve videos from the same category
     * (excluding the current video)
     */
    public function relatedByCategory(Video $currentVideo, int $limit = 6)
    {
        return Video::where('category_id', $currentVideo->category_id)
            ->where('id', '!=', $currentVideo->id)
            ->latest()
            ->limit($limit)
            ->get();
    }
}
