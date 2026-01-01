<?php

declare(strict_types=1);

namespace Atannex\Components\Sections;

use App\Models\Posts\Post;
use Illuminate\Support\Collection;

trait HasEditorPick
{
    /**
     * Get posts currently marked as active editor's pick.
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
