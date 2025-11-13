<?php

namespace Atannex\Sections\GetPosts;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait ByEditorPick
{
    /**
     * Get posts marked as editor's pick.
     */
    public function getEditorPick(int $limit = 5): Collection
    {
        return Post::query()
            ->editorPick()
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
