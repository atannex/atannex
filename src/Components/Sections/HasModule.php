<?php

declare(strict_types=1);

namespace Atannex\Components\Sections;

use App\Enums\Flag;
use App\Models\Modules\PostModule;

trait HasModule
{
    public function hasModulePostBySlug(string $slug): PostModule
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
