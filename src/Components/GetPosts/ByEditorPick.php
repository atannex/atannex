<?php

namespace Atannex\Components\GetPosts;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait ByEditorPick
{
    /**
     * Retrieve posts marked as editor's picks, limited and ordered by most recent publication.
     *
     * @param array $config Optional configuration. Recognized key:
     *                      - 'limit' => int Number of posts to retrieve (required).
     *                        If omitted, accessing this key will cause an undefined index notice.
     * @return Collection<int, Post> Collection of Post models flagged as editor's picks, filtered to published posts and ordered by `published_at` descending.
     */
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