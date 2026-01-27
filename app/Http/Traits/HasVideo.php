<?php

namespace App\Http\Traits;

use Illuminate\View\View;

trait HasVideo
{
    public function video():View
    {
        return view('videos.video');
    }

    public function catalog():View
    {
        return view('videos.catalog');
    }
}
