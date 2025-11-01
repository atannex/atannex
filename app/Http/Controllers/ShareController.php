<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Modules\PostModule;
use Atannex\Services\ShareService;
use Illuminate\Http\RedirectResponse;
use Atannex\Traits\HasUserTracking;

class ShareController extends Controller
{
    use HasUserTracking;

    public function __construct(
        protected readonly ShareService $shareService,
    ) {}

    public function share(string $platform, string $slug): RedirectResponse
    {
        $module = PostModule::whereHas('post', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })
            ->with('post')
            ->first();

        $post = $module->post;

        $urls = $this->shareService->generate(
            url($post->slug),
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
