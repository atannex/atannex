<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Atannex\Binders\HasPost;
use App\Models\Posts\Post;
use Atannex\Concerns\HasUserTracking;
use Atannex\Services\ShareService;
use Illuminate\Http\RedirectResponse;

class ShareController extends Controller
{
    use HasUserTracking;

    public function __construct(
        protected readonly ShareService $shareService,
        protected readonly HasPost $hasPost,
    ) {}

    public function share(string $platform, Post $post): RedirectResponse
    {
        $urls = $this->shareService->generate(
            url("/{$post->slug}/"),
            $post->title
        );

        $this->shareService->recordShare($post, $platform);

        $this->trackActivity('share_click', [
            'post_id'   => $post->id,
            'slug'      => $post->slug,
            'platform'  => $platform,
            'clicked_at' => now()->toDateTimeString(),
        ]);

        return redirect()->away($urls[$platform]);
    }
}
