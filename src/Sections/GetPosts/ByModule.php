<?php

namespace Atannex\Sections\GetPosts;

use App\Models\Modules\PostModule;

trait ByModule
{
    public function getModulePostBySlug(string $slug): PostModule
    {
        return PostModule::with([
            'post.category',
            'post.author',
            'post.tags',
        ])->whereHas('post', function ($query) use ($slug) {
                $query->where('slug', $slug)
                    ->published();
            })
            ->firstOrFail();
    }
}
