<?php

declare(strict_types=1);

namespace Atannex\Components\Sections;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait HasEditorPick
{
    /**
     * Retrieve the most recent posts marked as active editor's picks.
     *
     * @param  int  $limit  Maximum number of posts to return.
     * @return \Illuminate\Support\Collection Collection of Post models matching published and active editor's pick criteria, ordered by `editor_pick_at` descending.
     */
    public function hasEditorPick(int $limit = 5): Collection
    {
        return Post::query()
            ->published()
            ->activeEditorPick()
            ->orderBy('editor_pick_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
