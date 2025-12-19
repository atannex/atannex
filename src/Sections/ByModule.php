<?php

namespace Atannex\Sections;

use App\Enums\Flag;
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
                ->published()
                ->flagged(Flag::PUBLISHED);
        })
            ->firstOrFail();
    }
}
