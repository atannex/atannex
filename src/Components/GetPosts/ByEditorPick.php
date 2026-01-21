<?php

namespace Atannex\Components\GetPosts;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait ByEditorPick
{
    public function getEditorPicks(array $config = []): Collection
    {
        $limit = $config['limit'];

        return Post::query()
            ->ActiveEditorPick()
            ->published()
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }
}
