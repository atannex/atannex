<?php

namespace App\Http\Traits;

use App\Enums\Flag;
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

    public function show(string $slug): View
    {
        $modules = VideoModule::whereHas('video', function ($query) use ($slug) {
            $query->where('slug', $slug)->published();
        })
            ->with('video')
            ->firstOrFail();

        return view('videos.show', compact('modules'));
    }
}
