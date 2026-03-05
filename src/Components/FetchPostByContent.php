<?php

declare(strict_types=1);

namespace Atannex\Components;

use App\Models\Posts\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

trait FetchPostByContent
{
    /*
    |--------------------------------------------------------------------------
    | Breaking News
    |--------------------------------------------------------------------------
    */

    public function getBreakingPosts(array $config = []): Collection
    {
        $limit = $config['limit'];
        $sort = $config['sort'];
        $order = $config['order'];

        return $this->basePostQuery()
            ->breaking()
            ->orderBy($sort, $order)
            ->limit($limit)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Editor Picks
    |--------------------------------------------------------------------------
    */

    public function getEditorPicks(array $config = []): Collection
    {
        $limit = $config['limit'];

        return $this->basePostQuery()
            ->activeEditorPick()
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Recent Posts
    |--------------------------------------------------------------------------
    */

    public function getRecentPosts(array $config = []): Collection
    {
        $limit = $config['limit'];
        $sort = $config['sort'];
        $order = $config['order'];

        return $this->basePostQuery()
            ->whereHas('author')
            ->orderBy($sort, $order)
            ->limit($limit)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Base Query
    |--------------------------------------------------------------------------
    */

    private function basePostQuery(): Builder
    {
        return Post::query()->published();
    }
}
