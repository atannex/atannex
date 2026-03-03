<?php

use App\Enums\Flag;
use App\Enums\Status;
use App\Models\Docs\Document;
use App\Models\Posts\Post;
use App\Models\Regions\Category;
use App\Models\Regions\Employee;
use App\Models\Tags\Tag;
use Illuminate\Database\Eloquent\Builder;

if (! function_exists('author_exists')) {
    function author_exists(string $slug): bool
    {
        return Employee::query()
            ->where('status', Status::ACTIVE)
            ->whereHas(
                'user',
                fn(Builder $query) =>
                $query->where('slug', $slug)
            )
            ->exists();
    }
}

if (! function_exists('document_exists')) {
    function document_exists(string $slug): bool
    {
        return Document::query()
            ->flagged(Flag::PUBLISHED)
            ->where('slug_path', $slug)
            ->exists();
    }
}

if (! function_exists('category_exists')) {
    function category_exists(string $slug): bool
    {
        return Category::query()
            ->where('flag', Flag::PUBLISHED)
            ->where('slug_path', $slug)
            ->exists();
    }
}

if (! function_exists('tag_exists')) {
    function tag_exists(string $slug): bool
    {
        return Tag::query()
            ->where('slug', $slug)
            ->exists();
    }
}

if (! function_exists('post_exists')) {
    function post_exists(string $slug): bool
    {
        return Post::query()
            ->published()
            ->where('slug_path', $slug)
            ->exists();
    }
}
