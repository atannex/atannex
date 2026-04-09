<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Posts\Post;
use Atannex\Services\ShareService;
use Illuminate\Http\RedirectResponse;

class ShareController extends Controller
{
    public function __construct(
        protected readonly ShareService $shareService,
    ) {}

    /**
     * Handle a share request and redirect to the external platform.
     */
    public function share(string $platform, Post $post): RedirectResponse
    {
        $post->recordShare($platform);

        return redirect()->away(
            $this->shareService->generateFor($platform, url("/posts/{$post->slug_path}/"), $post->title)
        );
    }
}
