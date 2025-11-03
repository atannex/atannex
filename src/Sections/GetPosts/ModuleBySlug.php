<?php

namespace Atannex\Sections\GetPosts;

use App\Models\Posts\Post;
use App\Models\Modules\PostModule;

trait ModuleBySlug
{
    /**
     * Get the first post by its slug.
     *
     * @param string $slug
     * @return Post
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
