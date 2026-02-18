<?php

declare(strict_types=1);

namespace Atannex\Components\Sections;

use App\Enums\Flag;
use App\Models\Modules\PostModule;

trait HasModule
{
    /**
     * Retrieve the PostModule for a post identified by its slug that is published and flagged as published.
     *
     * @param  string  $slug  The post's slug.
     * @return PostModule The matching PostModule with `post.category`, `post.author`, and `post.tags` relations loaded.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If no matching PostModule is found.
     */
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
