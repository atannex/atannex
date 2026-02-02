<?php

namespace App\Http\Traits;

use App\Models\Posts\Video;
use App\Models\Posts\VideoModule;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

trait HasVideo
{
    /**
     * Get the list of published videos for a view.
     *
     * @return View
     */
    public function video(): View
    {
        $videos = Video::published()
            ->select('id', 'title', 'slug', 'category_id') // Only needed columns
            ->latest()
            ->get();

        return view('videos.video', compact('videos'));
    }

    /**
     * Show the catalog page for videos with optional pagination.
     *
     * @return View
     */
    public function catalog(): View
    {
        $videos = Video::published()
            ->select('id', 'title', 'slug', 'category_id')
            ->latest()
            ->paginate(12); // Paginate for large collections

        return view('videos.catalog', compact('videos'));
    }

    /**
     * Show a single video page by slug.
     *
     * @param string $slug
     * @return View
     */
    public function show(string $slug): View
    {
        $module = VideoModule::with('video')
            ->whereHas('video', fn($query) => $query->published()->where('slug', $slug))
            ->firstOrFail();

        $video = $module->video;

        // Map images safely and filter out empty entries
        /** @var Collection<int, array> $images */
        $images = collect($module->images ?? [])
            ->filter(fn($img) => !empty($img['file']))
            ->map(fn($img) => [
                'file' => $img['file'],
                'caption' => $img['caption'] ?? null,
                'alt' => $img['alt'] ?? null,
            ]);

        $relatedVideos = $this->relatedByCategory($video);

        return view('videos.show', compact('video', 'module', 'images', 'relatedVideos'));
    }

    /**
     * Retrieve videos from the same category
     * excluding the current video.
     *
     * @param Video $currentVideo
     * @param int $limit
     * @return Collection<int, Video>
     */
    public function relatedByCategory(Video $currentVideo, int $limit = 6): Collection
    {
        return Video::published()
            ->where('category_id', $currentVideo->category_id)
            ->where('id', '!=', $currentVideo->id)
            ->latest()
            ->limit($limit)
            ->get();
    }
}
