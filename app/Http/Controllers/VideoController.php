<?php

namespace App\Http\Controllers;

use App\Models\Modules\VideoModule;
use App\Models\Posts\Video;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $videos = Video::orderByDesc('created_at')->paginate(10);

        return view('videos.index', [
            'videos' => $videos
        ]);
    }

    public function show(string $slug): View
    {
        $module = VideoModule::with('video')
            ->whereHas('video', fn($q) => $q->where('slug', $slug))
            ->firstOrFail();

        return view('videos.show', [
            'module' => $module
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
