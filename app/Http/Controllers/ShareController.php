<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Posts\Post;
use Atannex\Binders\HasPost;
use Atannex\Services\ShareService;
use Illuminate\Http\RedirectResponse;

class ShareController extends Controller
{
    /**
     * Inject required services.
     *
     * @param ShareService $shareService Handles URL generation and share tracking.
     * @param HasPost      $hasPost      Trait/binder for resolving Post instances.
     */
    public function __construct(
        protected readonly ShareService $shareService,
        protected readonly HasPost $hasPost,
    ) {}

    /**
     * Generate and redirect to a share URL for the given platform.
     *
     * This method:
     *  1. Generates platform-specific share URLs using the post's public slug path.
     *  2. Records the share event for analytics.
     *  3. Tracks user activity for audit or behavioral insight.
     *  4. Redirects the user to the external sharing endpoint.
     *
     * @param string $platform The social platform (e.g., facebook, twitter, whatsapp).
     * @param Post   $post     The post being shared, auto-resolved by route model binding.
     *
     * @return RedirectResponse Redirects to the external share link.
     */
    public function share(string $platform, Post $post): RedirectResponse
    {
        $urls = $this->shareService->generate(
            url("/{$post->slug_path}/"),
            $post->title
        );

        return redirect()->away($urls[$platform]);
    }
}
