<?php

namespace Atannex\Components\For\Editorials;

use App\Enums\Flag;
use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait GetEditorPick
{
    /**
     * Retrieve editor's picks.
     *
     * This method fetches posts flagged as editorial picks, ensuring
     * only published posts are returned. Results are ordered by
     * the most recently updated posts.
     *
     * @param array $config Optional configuration:
     *                      - 'limit' => int Number of posts to retrieve (default 5)
     * @return Collection<int, Post>
     */
    public function getEditorPicks(array $config = []): Collection
    {
        $limit = $config['limit'] ?? 5;

        return Post::query()
            ->where('flag', Flag::EDITORIAL_PICK)
            ->published()
            ->orderByDesc('updated_at')
            ->limit($limit)
            ->get();
    }
}
