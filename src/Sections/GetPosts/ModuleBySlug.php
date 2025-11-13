<?php

namespace Atannex\Sections\GetPosts;

use App\Models\Modules\PostModule;
use App\Models\Posts\Post;

trait ModuleBySlug
{
    /**
     * Get the first post by its slug.
     */
    public function getModulePostBySlug(string $slug): Post
    {
        $module = PostModule::whereHas('post', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })
            ->with('post')
            ->first();

        return $module->post;
    }
}
